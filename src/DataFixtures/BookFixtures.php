<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getBooks() as $book) {
            $manager->persist($book);
        }

        $manager->flush();
    }

    private function getBooks(): iterable
    {
        foreach ($this->getBooksData() as $datum) {
            $book = (new Book())
                ->setTitle($datum['title'])
                ->setCover($datum['cover'])
                ->setAuthor($datum['author'])
                ->setIsbn($datum['isbn'])
                ->setPlot($datum['plot'])
                ->setReleasedAt(new \DateTimeImmutable($datum['releasetAt']));
            yield $book;
        }
    }
    private function getBooksData(): iterable
    {
        $files = (new Finder())
            ->in(__DIR__)
            ->files()
            ->name('book_fixtures.json');
        foreach ($files as $file) {
            $content = $file->getContents();
            foreach (json_decode($content, true) as $data) {
                yield $data;
            }
        }
    }
}
