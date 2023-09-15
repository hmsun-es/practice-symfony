<?php

namespace App\Controller;

use App\Repository\CodeigniterModel\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SampleController extends AbstractController
{
    #[Route('/', name: 'app_sample')]
    public function index(ProductRepository $repo): Response
    {
        $products = $repo->findAll();

        return $this->render('sample/index.html.twig', [
            'controller_name' => 'SampleController',
        ]);
    }
}
