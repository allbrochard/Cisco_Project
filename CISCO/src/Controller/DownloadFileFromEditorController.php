<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\DownloadFile;
use Symfony\Component\Routing\Annotation\Route;

class DownloadFileFromEditorController extends AbstractController
{
    /**
     * @Route("/download", name="download")
     */
    public function index(DownloadFile $downloadFile)
    {

        $response = $downloadFile->downloadFile('test', 'test123456789');
        return $this->render('download_file_from_editor/index.html.twig', array('response'=>$response));
    }
}
