<?php

namespace App\Tests;

use App\Helper\PathGenerator;
use Fidry\AliceDataFixtures\LoaderInterface;

trait FixtureLoaderTrait
{
    /**
     * To load fixture before to run a test.
     */
    public function loadFixture(array $fixtureFiles): array
    {
        $fixturePath = $this->getFixturesPath();
        $files = array_map(fn ($fixtureFiles) => PathGenerator::join($fixturePath, $fixtureFiles.'.yml'),
            $fixtureFiles);
        /** @var LoaderInterface $loader */
        $loader = $this->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');

        return $loader->load($files);
    }

    private function getFixturesPath(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR;
    }
}
