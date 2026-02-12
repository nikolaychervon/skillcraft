<?php

namespace App\Actions\User;

use App\DTO\User\CreatingUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateNewUserAction
{
    public function run(CreatingUserDTO $creatingUserDTO): User
    {
        return User::query()->create([
            'first_name' => $creatingUserDTO->getFirstName(),
            'last_name' => $creatingUserDTO->getLastName(),
            'middle_name' => $creatingUserDTO->getMiddleName(),
            'email' => $creatingUserDTO->getEmail(),
            'password' => Hash::make($creatingUserDTO->getPassword()),
            'unique_nickname' => $creatingUserDTO->getUniqueNickname(),
        ]);
    }
}
