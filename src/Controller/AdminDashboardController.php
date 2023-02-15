<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\LoginType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends BaseController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin_dashboard/index.html.twig', [
            'controller_name' => 'AdminDashboardController',
        ]);
    }


    #[Route('/admin', name: 'admin')]
    public function admin_login() {
        $formLogin = $this->createForm(LoginType::class);
            $formLogin->handleRequest($this->request);
                if ($formLogin->isSubmitted() && $formLogin->isValid()) {
                    $data = $formLogin->getData();
                    $admin=$this->managerRegistry->getRepository(Admin::class)->findOneBy((['Email' => $data->getEmail()]));
                    if($admin && password_verify($data->getPassword(), $admin->getPassword())){
                        $this->session->set('User_name',$admin->getNom() );
                        $this->session->set('Admin_id',$admin->getId() );
                        $this->session->set('Session_time', new DateTime());
                        return $this->redirect("/admin/dashboard");
                    }
                }
        return $this->renderForm('admin_dashboard/login.html.twig',[
            'controller_name' => 'AdminDashboardController','formLogin'=>$formLogin
        ]);
    }

    #[Route('/logout_admin', name: 'logout_admin')]
    public function logout() {
        $this->session->invalidate();
        return $this->redirect("/admin");
    }
}
