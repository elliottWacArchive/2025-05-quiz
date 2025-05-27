<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController
{
    #[Route('/')]
    public function index()
    {
        return new Response('Welcome to the Main Controller!');
    }
}