<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MoviesController extends AbstractController
{
  private $repository;

  public function __construct(EntityManagerInterface $em)
  {
    $this->repository = $em->getRepository(Movie::class);
  }
  #[Route('/movies', name: 'app_movies', methods: ['GET'])]
  public function index(MovieRepository $movieRepository): Response
  {
    // Easy but limited
    $movies = $movieRepository->findAll();

    // this method is the most use (because it have more method)
    $movies2 = $this->repository->findBy([], ['id' => 'DESC']);

    // dd($movies2);
    return $this->render('index.html.twig', [
      'movies' => $movies2,
    ]);
  }

  #[Route('/movies/{title}', name: 'app_movies_get', methods: ['GET', 'HEAD'])]
  public function movie($title): JsonResponse
  {
    // findOneBy() - SELECT * FROM movies WHERE id = 6 AND title = 'The Drak Knight' ORDER BY id DESC
    $movie = $this->repository->findOneBy(['title' => "The Dark Knight"]);

    // dd($movie);
    return $this->render('index.html.twig', [
      'movies' => $movie,
    ]);
  }
}
