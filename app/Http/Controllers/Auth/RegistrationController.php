<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\HashEmail;
use App\Helpers\Respond;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Mail\ConfirmRegistrationEmail;
use App\Models\User;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    /**
     * Register new User.
     *
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        // Retrieve the validated input data...
        $validatedRequestData = $request->validated();

        $email = $validatedRequestData['email'];

        $userData = User::create(array_merge(
            $validatedRequestData,
            ['password' => bcrypt($request->password)]
        ));

        // Encrypt email (it will be used as confirmation token)
        $confirmationToken = HashEmail::encrypt($email);

        // TODO: Send confirmation link
        Mail::to($email)->send(new ConfirmRegistrationEmail($confirmationToken));

        return Respond::ok('Confirmation email has been sent to ' . $email);
    }


    /**
     * Confirm Email.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmEmail (Request $request): JsonResponse
    {
        $confirmationHash = $request->token;
        $email = HashEmail::decrypt($confirmationHash);

        // Set user status to 'active'
        $updated = User::query()
            ->where('email', $email)
            ->where('status', '=', 'pending')
            ->update([
                'status' => 'active',
                'email_verified_at' => now()
            ]);
        if(! $updated) {
            return Respond::error('INVALID_CONFIRMATION_TOKEN');
        }

        return Respond::ok('Email confirmed!');
    }
}
