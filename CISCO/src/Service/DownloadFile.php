<?php
/**
 * Created by PhpStorm.
 * User: allan
 * Date: 15/07/2019
 * Time: 13:38
 */

namespace App\Service;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadFile
{
    public function downloadFile($titre, $contenue){

        $fileContent = $contenue; // the generated file content
        $response = new Response($fileContent);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $titre.'.txt'
        );

        $response->headers->set('Content-Disposition', $disposition);
    }
}