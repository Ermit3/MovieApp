<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MoviesService extends AbstractController
{
  public function buildImagePath($imagePath, $movie)
  {
    $newFileName = uniqid() . '.' . $imagePath->guessExtension();

    try {
      $imagePath->move(
        $this->getParameter('kernel.project_dir') . '/public/uploads',
        $newFileName
      );
    } catch (FileException $e) {
      return new Response($e->getMessage());
    }

    $movie->setImagePath('/uploads/' . $newFileName);
    return $movie;
  }
}