<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\AssignRoleRequest;
use App\Http\Requests\User\UpdateUserRequest;


class UserController extends Controller
{

    protected UserService $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(Request $request): JsonResponse
    {
        $users = $this->UserService->getUsers($request);
        return self::success($users, 'Users retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->UserService->storeUser($request->validationData());
        return self::success($user, 'User created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return self::success($user, 'User retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->UserService->updateUser($user, $request->validationData());
        return self::success($updatedUser, 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    { if(Auth::check() && $user->id === Auth::id())
        $user->delete();
        return self::success(null, 'User deleted successfully');
    }

    /**
     * Assign a role to the user.
     * @param User $user The user to assign the role to.
     * @param AssignRoleRequest $role The role to assign.
     * @return JsonResponse A JSON response with a success message.
     */
    public function assignRoleToUser(User $user, AssignRoleRequest $role): JsonResponse
    {
        // Assign the role to the user.
         $user->syncRoles($role->validated());
        // Return a JSON response with a success message.
        return self::success($user, 'User retrieved successfully');
    }

}
