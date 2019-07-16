<?php
/**
 * Created by PhpStorm.
 * User: allan
 * Date: 15/07/2019
 * Time: 13:38
 */
namespace App\Service;

//use Symfony\Component\Validator\Constraints\Date;

class DownloadFile
{
    public function downloadFile($titre, $contenue){
        $date = new \DateTime();
        
        $filename = $titre . $date->format('dmy') . '.txt';
        
        
        file_put_contents($filename, $contenue);
        // Generate response
        $response = new \Symfony\Component\HttpFoundation\Response();
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
        $response->headers->set('Content-length', filesize($filename));
        // Send headers before outputting anything
        $response->sendHeaders();
        $response->setContent(file_get_contents($filename));
        dump($response);
        die;
        return $response;
    }
}