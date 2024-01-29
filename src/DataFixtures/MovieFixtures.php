<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MovieFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $movie = new Movie();
    $movie->setTitle("The Dark Knight");
    $movie->setDescription("This is the description of the dark knight");
    $movie->setReleaseYear(2008);
    $movie->setImagePath("https://th.bing.com/th/id/R.b080a8360003775e4eaf4c15df0d87f2?rik=jEQ%2fNHJdUpNQ3Q&riu=http%3a%2f%2fdailygrindhouse.com%2fwp-content%2fuploads%2f2016%2f07%2fmaxresdefault.jpg&ehk=0T1NOrstZOpOH52d%2bH5QMy19Lofh5MDpbBlO1fdqZ1Y%3d&risl=&pid=ImgRaw&r=0");
    // Add data to pivot table
    $movie->addActor($this->getReference("actor_1"));
    $movie->addActor($this->getReference("actor_2"));
    $manager->persist($movie);

    $movie2 = new Movie();
    $movie2->setTitle("Avengers: Endgame");
    $movie2->setDescription("This is the description of Avengers: Endgame");
    $movie2->setReleaseYear(2019);
    $movie2->setImagePath("https://images.hdqwalls.com/download/avengers-endgame-10k-jj-1920x1080.jpg");
    // Add data to pivot table
    $movie2->addActor($this->getReference("actor_3"));
    $movie2->addActor($this->getReference("actor_4"));
    $manager->persist($movie2);

    $manager->flush();
  }
}