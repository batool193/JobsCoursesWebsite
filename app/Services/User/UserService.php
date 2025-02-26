<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class UserService
{
    /**
     * Retrieve all users with pagination.
     *
     * Fetch paginated users
     * Log the exception and throw it
     * @return LengthAwarePaginator
     */
    public function getUsers($request)
    {
        return User::paginate(10);
    }

    /**
     * Create a new user with the provided data.
     *
     * @param array $data The validated data to create a user.
     * @return User|null The created user object on success, or null on failure.
     */
    public function storeUser(array $data): ?User
    {
        $user = User::create($data);
        $company = Company::create(['name'=>$data['name'],'email'=>$data['email'],'user_id'=>$user->id]);
        $user->assignRole('company');
        return $user;
    }

    /**
     * Update an existing user with the provided data.
     *
     * @param User $user The user to update.
     * @param array $data The validated data to update the user.
     * @return User|null The updated user object on success, or null on failure.
     */
    public function updateUser(User $user, array $data): ?User
    {
        if(Auth::check() && $user->id === Auth::id())
        $user->update(array_filter($data));
        return $user;
    }



}
