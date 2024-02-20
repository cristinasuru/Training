<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/index', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'Index',
        ]);
    }
    #[Route('/contact', name: 'app_main_contact')]
    public function contact(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'Contact',
        ]);
    }
}
