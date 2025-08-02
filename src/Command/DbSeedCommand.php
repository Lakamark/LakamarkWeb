<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'db:seed',
    description: 'Seed the database',
)]
class DbSeedCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Lunch the hautelook command to seed the database
        $command = $this->getApplication()->find('hautelook:fixtures:load');
        $result = $command->run($input, $output);

        if (Command::SUCCESS !== $result) {
            $io->error('Something went wrong!');

            return $result;
        }

        $io->success('Your database has been seeder successfully!');

        return Command::SUCCESS;
    }
}
