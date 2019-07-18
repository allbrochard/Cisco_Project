<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PortController extends AbstractController
{
    /**
     * @Route("/port/{name}", name="port")
     */
    public function index($name)
    {
        return $this->render('port.html.twig', [
            'port_name' => $name,
        ]);
    }
}