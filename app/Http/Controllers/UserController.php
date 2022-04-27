<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Creates new user
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create (CreateRequest $request)
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
