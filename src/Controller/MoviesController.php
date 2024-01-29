<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies', name: 'app_movies', methods: ['GET'])]
    public function index(): Response
    {
        $movies = ["Avengers: Endgame", "Iron man", "Black Panthere", "Hulk"];
        return $this->render('index.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/movies/{name}', name: 'app_movies_get', methods: ['GET', 'HEAD'])]
    public function movie($name): JsonResponse
    {
        return $this->json([
            'message' => $name,
            'path' => 'src/Controller/MoviesController.php',
        ]);
    }
}
