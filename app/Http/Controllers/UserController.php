<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\DeleteRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Creates new user
     *
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function create (CreateRequest $request): JsonResponse
    {
        // Retrieve the validated input data...
        $validatedRequestData = $request->validated();

        $userData = User::query()->create(array_merge(
            $validatedRequestData,
            ['password' => bcrypt($request->password)]
        ));

        return response()->json($userData);
    }

    /**
     * Delete User (by ID)
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete (DeleteRequest $request): JsonResponse
    {
        // Delete from database
        User::destroy($request->id);

        return response()->json([
            'status' => 'ok',
            'message' => 'User deleted!'
        ]);
    }
}
