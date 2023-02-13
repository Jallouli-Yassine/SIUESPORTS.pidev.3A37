<?php

namespace App\Controller;

use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BaseController extends AbstractController
{
    public $session;
    public $passwordhash;
    public $managerRegistry;
    public $sessionLifetime=36;
    public $request;

    
    public function __construct(SessionInterface $session,RequestStack $requestStack ,ManagerRegistry $managerRegistry,UserPasswordHasherInterface $passwordHasher)
    {
        $this->managerRegistry = $managerRegistry;
        $this->passwordhash = $passwordHasher;
        $this->request=$requestStack->getCurrentRequest();
        $this->session = $this->request->getSession();
        $this->check_session();   
    }

   

    public function check_session():bool{
        $this->session=$this->request->getSession();
        if ( $this->session->has('Gamer_id') ||  $this->session->has('Coach_id')) {
            $diff= $this->session->get('Session_time');
            $now=new DateTime();            
            $difference = $diff->getTimestamp() - $now->getTimestamp();
            if ($difference>60) {
                 $this->session->invalidate();
                 return False;
            } else {
                return True;
            }
        }
        else
        return False;
    }
    
    





}
