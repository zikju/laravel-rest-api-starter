<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
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
}
