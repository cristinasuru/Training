<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\Consumer\MovieConsumerInterface;
use App\Movie\Enum\SearchType;
use App\Repository\MovieRepository;
use App\Transformer\OmdbToGenreTransformer;
use App\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider
{
    public function __construct(
        protected EntityManagerInterface $manager,
        protected MovieConsumerInterface $consumer,
        protected OmdbToMovieTransformer $movieTransformer,
        protected GenreProvider $provider,
    )
    {
    }

    public function getMovie(SearchType $type, string $value): Movie
    {

        if ($movie = $this->manager->getRepository(Movie::class)->getMovieByTitle($type, $value)) {
            return $movie;
        } else {
            $data = $this->consumer->fetchMovieData($type, $value);
            $movie = $this->movieTransformer->transform($data);
            foreach ($this->provider->getFromOmdb($data) as $genre) {
                $movie->addGenre($genre);
            }
            $this->manager->persist($movie);
            $this->manager->flush();
            return $movie;
        }

    }

}