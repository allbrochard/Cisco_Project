<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\DownloadFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DownloadFileFromEditorController extends AbstractController
{
    /**
     * @Route("/download/{fichier}", name="download")
     */
    public function index(DownloadFile $downloadFile, $fichier)
    {

        $response = $downloadFile->downloadFile('test', $fichier);
        return $response;
    }
}
