<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    return $this->render('movies/index.html.twig', [
      'movies' => $movies2,
    ]);
  }

  #[Route('/movies/{id}', name: 'movies_show', methods: ['GET', 'HEAD'])]
  public function show($id): Response
  {
    // findOneBy() - SELECT * FROM movies WHERE id = 6 AND title = 'The Drak Knight' ORDER BY id DESC
    $movie = $this->repository->findOneBy(['id' => $id]);

    // dd($movie);
    return $this->render('movies/show.html.twig', [
      'movie' => $movie,
    ]);
  }

  #[Route('/movies/edit/{id}/', name: 'movies_edit', methods: ['GET', 'HEAD'])]
  public function edit($id): Response
  {
    // findOneBy() - SELECT * FROM movies WHERE id = 6 AND title = 'The Drak Knight' ORDER BY id DESC
    $movie = $this->repository->findOneBy(['id' => $id]);

    // dd($movie);
    return $this->render('movies/show.html.twig', [
      'movie' => $movie,
    ]);
  }
}
