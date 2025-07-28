<?php

namespace App\Foundation\Maker;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('create:entity')]
class MakerEntityCommand extends AbstractMakerCommand
{
    protected function configure(): void
    {
        $this
            ->setDescription('Generate an entity in a selected domain')
            ->addArgument('entityName', InputArgument::OPTIONAL, 'Entity name');
    }

    /**
     * @throws |ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $domain = $this->askDomain($io);

        /** @var string $entity */
        $entity = $input->getArgument('entityName');

        /** @var Application $commandApplication */
        $commandApplication = $this->getApplication();
        $command = $commandApplication->find('make:entity');
        $arguments = [
            'command' => 'make:entity',
            'name' => "\\App\\Domain\\$domain\\Entity\\$entity",
        ];
        $greetInput = new ArrayInput($arguments);

        return $command->run($greetInput, $output);
    }
}
