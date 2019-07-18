<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PortController extends AbstractController
{
    /**
     * @Route("/port", name="port")
     */
    public function index()
    {
        return $this->render('port/index.html.twig', [
            'controller_name' => 'PortController',
        ]);
    }
}
