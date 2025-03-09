<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserEmail;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    
    public function findById(UserId $id): ?User;
    
    public function findByEmail(UserEmail $email): ?User;
    
    public function delete(UserId $id): void;
} 