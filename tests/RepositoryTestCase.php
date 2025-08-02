<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @template E
 */
class RepositoryTestCase extends KernelTestCase
{
    /**
     * @var E
     */
    protected mixed $repository;

    /**
     * @var class-string<E>
     */
    protected string $repositoryClass;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = self::getContainer()->get($this->repositoryClass);
    }
}
