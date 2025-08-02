<?php

namespace App\Tests\Repository;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Repository\UserRepository;
use App\Tests\FixtureLoaderTrait;
use App\Tests\RepositoryTestCase;

/**
 * @property UserRepository $repository
 */
class UserRepositoryTest extends RepositoryTestCase
{
    use FixtureLoaderTrait;

    protected string $repositoryClass = UserRepository::class;

    public function testUserCount(): void
    {
        /** @var $dummy1 User */
        /** @var $dummy2 User */
        ['dummy1' => $dummy1, 'dummy2' => $dummy2] = $this->loadFixture(['users']);
        $users = $this->repository->count();
        $this->assertEquals(7, $users);
    }

}
