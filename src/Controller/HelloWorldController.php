<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloWorldController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function hello(): Response
    {
        return $this->render('Hello/index.html.twig', [
            'controller_name' => 'hello'
        ]);
    }
    #[Route('/hello/{name?world}', name: 'app_hello_name', requirements: ['name' => '[a-zA-Z]+'])]
    public function helloName(string $name): Response
    {
        return $this->render('Hello/index.html.twig', [
            'controller_name' => $name
        ]);
    }

}