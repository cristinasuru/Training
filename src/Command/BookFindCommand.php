<?php

namespace App\Command;

use App\Entity\Movie;
use App\Movie\Consumer\MovieConsumerInterface;
use App\Movie\Enum\SearchType;
use App\Movie\Provider\GenreProvider;
use App\Movie\Provider\MovieProvider;
use App\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:book:find',
    description: 'Add a short description for your command',
)]
class BookFindCommand extends Command
{
    public function __construct(
        protected MovieProvider $provider,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
//            ->addArgument('titleType', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('value', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $value = $input->getArgument('value');
        $type = $io->ask('Is it a OMDB id or a movie title?', 'OMDB id', 'movie title');

        if ($type === 'movie title') {
        $io->note('Searching for movie in database..');
        /** @var Movie $movie */
        $movie = $this->manager->getRepository(Movie::class)->getMovieByTitle(SearchType::Title, $value);
        if (!$movie) {
            $io->note('Movie not found!');
            return Command::FAILURE;
        }
        $io->note('getting movie details...');
        $io->title($movie->getTitle());
        $io->table([
            'Id',
            'IMDB Id',
            'Title',
            'MPAA restriction',
        ], [
            $movie->getId(),
            $movie->getImdbId(),
            $movie->getTitle(),
            $movie->getRated()
        ]);
        return Command::SUCCESS;
        } else {

            $data = $this->consumer->fetchMovieData(SearchType::Id, $value);
            $movie = $this->movieTransformer->transform($data);
            foreach ($this->provider->getFromOmdb($data) as $genre) {
                $movie->addGenre($genre);
            }
    }


//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
