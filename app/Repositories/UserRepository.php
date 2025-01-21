<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function getUsersByRole(string $role)
    {
        return User::where('role', $role)->get();
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }
}
