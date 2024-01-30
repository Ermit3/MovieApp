<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MoviesController extends AbstractController
{
  private $repository;
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->repository = $em->getRepository(Movie::class);
    $this->em = $em;
  }
  #[Route('/', name: 'movies', methods: ['GET'])]
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

  #[Route('/movies/create', name: 'movies_create')]
  public function create(Request $request): Response
  {
    $movie = new Movie();
    $form = $this->createForm(MovieFormType::class, $movie);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $newMovie = $form->getData();

      $imagePath = $form->get('imagePath')->getData();

      if ($imagePath) {
        $newFileName = uniqid() . '.' . $imagePath->guessExtension();

        try {
          $imagePath->move(
            $this->getParameter('kernel.project_dir') . '/public/uploads',
            $newFileName
          );
        } catch (FileException $e) {
          return new Response($e->getMessage());
        }

        $newMovie->setImagePath('/uploads/' . $newFileName);
      }

      $this->em->persist($newMovie);
      $this->em->flush();

      return $this->redirectToRoute('movies');
    }
    return $this->render('movies/create.html.twig', [
      'form' => $form->createView(),
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

  #[Route('/movies/edit/{id}/', name: 'movies_edit')]
  public function edit($id, Request $request): Response
  {
    // findOneBy() - SELECT * FROM movies WHERE id = 6 AND title = 'The Drak Knight' ORDER BY id DESC
    $movie = $this->repository->findOneBy(['id' => $id]);

    $form = $this->createForm(MovieFormType::class, $movie);
    $form->handleRequest($request);
    $imagePath = $form->get('imagePath')->getData();

    if ($form->isSubmitted() && $form->isValid()) {
      if ($imagePath) {
        if ($movie->getImagePath() !== null) {
          if (file_exists($this->getParameter('kernel.project_dir') . '/public' . $movie->getImagePath())) {
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
            $this->em->flush();
          }
        }
      } else {
        $movie->setTitle($form->get('title')->getData());
        $movie->setReleaseYear($form->get('releaseYear')->getData());
        $movie->setDescription($form->get('description')->getData());

        $this->em->flush();
      }

      return $this->redirectToRoute('movies_show', ['id'=> $id]);
    }
    return $this->render('movies/edit.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}
