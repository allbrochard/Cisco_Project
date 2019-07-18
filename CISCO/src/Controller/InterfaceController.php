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
        $name = str_replace('-', '/', $name);
        return $this->render('interface.html.twig', array(
            'interface_name' => $name,
        ));
    }
}
