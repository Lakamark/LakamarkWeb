<?php

namespace App\Foundation\Maker;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractMakerCommand extends Command
{
    private array $directories = [
        'Entity',
        'Repository',
        'Events',
        'Subscribers',
    ];

    public function __construct(
        private readonly Environment $twig,
        protected string $projectDir,
    ) {
        parent::__construct();
    }

    /**
     * To generate files.
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function generateFiles(string $template, array $params, string $output): void
    {
        $content = $this->twig->render("@maker/$template.twig", $params);
        $filename = "$this->projectDir/$output";
        $directory = dirname($filename);
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($filename, $content);
    }

    protected function askClass(string $question, string $pattern, SymfonyStyle $io, bool $multiple = false): array
    {
        // Build the array to autocomplete
        $classes = [];
        $paths = explode('/', $pattern);

        if (1 === count($paths)) {
            $directory = "$this->projectDir/src";
            $pattern = $pattern;
        } else {
            $directory = "$this->projectDir/src/".join('/', array_slice($paths, 0, -1));
            $pattern = join('/', array_slice($paths, -1));
        }

        $files = (new Finder())
            ->in($directory)
            ->name("$pattern.php")
            ->files();

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $filename = str_replace('.php', '', $file->getBasename());
            $classes[$filename] = $file->getPathname();
        }

        // Ask the question
        $q = new Question($question);
        $q->setAutocompleterValues(array_keys($classes));

        $answers = [];
        $replaces = [
            "$this->projectDir/src" => 'App',
            '/' => '\\',
            '.php' => '',
        ];

        while (true) {
            $class = $io->askQuestion($q);
            if (null === $class) {
                return $answers;
            }
            $path = $classes[$class];

            $answers[] = [
                'namespace' => str_replace(array_keys($replaces), array_values($replaces), $path),
                'class_name' => $class,
            ];

            // If the multiple option is false we return the first answer index
            if (false === $multiple) {
                return $answers[0];
            }
        }
    }

    /**
     * To ask to select a domain in the application.
     */
    protected function askDomain(SymfonyStyle $io): string
    {
        $domainsList = [];
        $files = (new Finder())
            ->in("$this->projectDir/src/Domain")
            ->depth(0)
            ->directories();

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $domainsList[] = $file->getBasename();
        }

        // Ask the question
        $q = new ChoiceQuestion('Please select the domain', $domainsList, 0);

        return $io->askQuestion($q);
    }
}
