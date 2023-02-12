<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Gamer;
use App\Entity\User;
use App\Form\CoachType;
use App\Form\GamerType;
use App\Form\LoginType;
use App\Security\Users;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class GestionEsportController extends AbstractController
{
    private $managerRegistry;
    private $passwordhash;

    public function __construct(private ManagerRegistry $managerRegistry2, UserPasswordHasherInterface $passwordHasher)
    {
        $this->managerRegistry = $managerRegistry2;
        $this->passwordhash = $passwordHasher;
    }

    // #[Route('/', name: 'home')]
    // public function welcomefront(Request $request): Response
    // {
    //     $vol = new Gamer();
    //     $form = $this->createForm(GamerType::class, $vol);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->managerRegistry->getManagerForClass(Gamer::class);
    //         $em->persist($vol);
    //         $em->flush();
    //     }
      
    //     return $this->renderForm('Front_Template/welcome.html.twig',[
    //         'controller_name' => 'GestionEsportController','form' => $form
    //     ]);
    // }

     /**
     * @Route("/", name="home")
     */
    public function welcomefront(Request $request): Response
    {
        $session = $request->getSession();
        $user_id = $session->get('Gamer_id');

        if($user_id==null){

        
        $em = $this->managerRegistry->getManagerForClass(Gamer::class);

        $user = new Gamer();
        $users=new Users();
        $user->setPoint(100);
        $form = $this->createForm(GamerType::class, $user);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                
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
                        $session = $request->getSession();
                        $session->set('Coach_id', $coach->getId());
                        return $this->render('coaching/allCoaching.html.twig', [
                            'controller_name' => 'CoachingController',
                        ]);
                    }

                }
                else if (!password_verify($data->getPassword(), $gamer->getPassword())) {
                    
                }else{
                    $session = $request->getSession();
                    $session->set('Gamer_id', $gamer->getId());
                    return $this->render('coaching/allCoaching.html.twig', [
                        'controller_name' => 'CoachingController',
                    ]);
                }
            }
      
            return $this->render('tournoi/tournoi.html.twig', [
                'controller_name' => 'TournoiController',
            ]);
    }else{
        return $this->render('tournoi/tournoi.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }
    }
}
