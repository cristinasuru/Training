<?php

namespace App\Movie\Provider;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Transformer\OmdbToGenreTransformer;

class GenreProvider
{
    public function __construct(
        protected readonly GenreRepository $genreRepository,
        protected readonly OmdbToGenreTransformer $genreTransformer,
    )
    {
    }

    public function getGenre(string $value): Genre
    {
        return $this->genreRepository->findOneBy(['name' => $value])
            ?? $this->genreTransformer->transform($value);
    }

    public function getFromOmdb(array $value): iterable
    {
        if (!\array_key_exists('Genre', $value)) {
            throw new \InvalidArgumentException();
        }

        foreach (explode(', ', $value['Genre']) as $name) {
            yield $this->getGenre($name);
        }
    }
}