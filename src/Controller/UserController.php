<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Gamer;
use App\Entity\RechargeCode;
use App\Entity\User;
use App\Form\RechargeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(Request $request): Response
    {   
        $this->session=$this->request->getSession();
        $code=new RechargeCode();
        $form = $this->createForm(RechargeType::class, $code);
        $form->handleRequest($request);
        if($this->session->has('Gamer_id')){
            $em2 = $this->managerRegistry->getRepository(Gamer::class);
            $user=$em2->findOneBy(['id' => $this->session->get('Gamer_id')]);
            
            if ($form->isSubmitted() && $form->isValid()) {
                // process the form data
                $result=$this->recharge($code->getCode(),$user);
                if($result){
                    return $this->redirect("/profile");
                }

                 return $this->renderForm('user/profile.html.twig', [
            'controller_name' => 'UserController','user'=>$user,"charge"=>$form,"historique"=>$user->getHistoriquePoints()
        ]);
            }
            
        }
        else if($this->session->has('Coach_id')){
            $em2 = $this->managerRegistry->getRepository(Coach::class);
            $user=$em2->findOneBy(['id' => $this->session->get('Coach_id')]);
        }
            return $this->renderForm('user/profile.html.twig', [
            'controller_name' => 'UserController','user'=>$user,"charge"=>$form,"historique"=>$user->getHistoriquePoints()
        ]);
    }
}
