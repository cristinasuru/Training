<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
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
    #[Route('/hello/{name?World}', name: 'app_hello_index', requirements: ['name' => '(?:\pL|[- ])+'])]
    public function index(string $name, #[Autowire(param: 'app.symfony_version')] string $sfVersion): Response
    {
        dump($sfVersion);
        return $this->render('hello/index.html.twig', [
            'controller_name' => $name,
        ]);
    }

}