<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Coach;
use App\Entity\Cours;
use App\Entity\Gamer;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\CoachType;
use App\Form\GamerType;
use App\Form\LoginType;
use App\Security\Users;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Util\Xml\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class GestionEsportController extends BaseController
{
    
    #[Route('/acceuil', name: 'acceuil')]
    public function acceuil(Request $request): Response{
        $this->session=$request->getSession();
        if(!$this->check_session()){
            return $this->redirect("/");
        }
        return $this->renderForm('Front_Template/acceuil.html.twig',[
            'controller_name' => 'GestionEsportController',
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout() {
        $this->session->invalidate();
        return $this->redirect("/");
    }
     
    #[Route('/', name: 'home')]
    public function signin_signout(Request $request): Response
    {   $this->session=$request->getSession();
        if($this->check_session()){
            return $this->redirect("/acceuil");
        }
            $em = $this->managerRegistry->getManagerForClass(Gamer::class);
            $user = new Gamer();
            $users=new Users();
            $user->setPoint(100);
            $form = $this->createForm(GamerType::class, $user);
            $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $em2 = $this->managerRegistry->getRepository(Gamer::class);
                    
                    $hashedPassword = $this->passwordhash->hashPassword(
                        $users,
                        $user->getPassword()
                    );
                    $user->setPassword($hashedPassword);
                    $em->persist($user);
                    $em->flush();
                }
            $formLogin = $this->createForm(LoginType::class);
            $formLogin->handleRequest($request);
                if ($formLogin->isSubmitted() && $formLogin->isValid()) {
                    $em2 = $this->managerRegistry->getRepository(Gamer::class);
                    $data = $formLogin->getData();
                    $gamer=new Gamer();
                    $gamer = $em2->findOneBy(['email' => $data->getEmail()]);
                    if (!$gamer) {
                        $coach=new Coach();
                        $em2 = $this->managerRegistry->getRepository(Coach::class);
                        $coach = $em2->findOneBy(['email' => $data->getEmail()]);
                        if (!$coach) {
                            
                        }
                        else if (!password_verify($data->getPassword(), $coach->getPassword())) {
                           
                        }else{
                            $this->session->set('Coach_id', $coach->getId());
                            $this->session->set('Solde', $coach->getPoint());
                            $this->session->set('User_name', $coach->getNom()." ".$coach->getPrenom());
                            $this->session->set('Session_time', new DateTime());
                            return $this->redirect("/acceuil");
                        }
                    }
                    else if (!password_verify($data->getPassword(), $gamer->getPassword())) {
                        
                    }else{
                        $this->session->set('User_name', $gamer->getTag());
                        $this->session->set('Solde', $gamer->getPoint());
                        $this->session->set('Gamer_id', $gamer->getId());
                        $this->session->set('Session_time', new DateTime());
                        return $this->redirect("/acceuil");
                    }
                }
                return $this->renderForm('Front_Template/welcome.html.twig',[
                    'controller_name' => 'GestionEsportController','formcreateuser' => $form,'formLogin'=>$formLogin
                ]);  
    }
}
