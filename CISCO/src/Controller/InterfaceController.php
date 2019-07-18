<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InterfaceController extends AbstractController
{
    /**
     * @Route("/interface/{name}", name="interface")
     */
    public function index($name)
    {
        return $this->render('interface/index.html.twig', [
            'controller_name' => 'InterfaceController',
            'interface_name' => $name,
        ]);
    }
}
