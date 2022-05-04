<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Respond;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Registration\ConfirmEmailRequest;
use App\Http\Requests\Auth\Registration\RegistrationRequest;
use App\Mail\ConfirmRegistrationEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        $confirmationToken = Str::uuid();

        User::create(array_merge(
            $validatedRequestData,
            [
                'password' => bcrypt($request->password),
                'confirmation_token' => $confirmationToken
            ]
        ));

        Mail::to($email)->send(new ConfirmRegistrationEmail($confirmationToken));

        return Respond::ok('Confirmation email has been sent to ' . $email);
    }


    /**
     * Confirm Email.
     *
     * @param ConfirmEmailRequest $request
     * @return JsonResponse
     */
    public function confirmEmail (ConfirmEmailRequest $request): JsonResponse
    {
        // Retrieve the validated input data...
        $validatedRequestData = $request->validated();

        $confirmationToken = $validatedRequestData['token'];

        // Set user status to 'active'
        $updated = User::query()
            ->where('confirmation_token', $confirmationToken)
            ->where('status', '=', 'pending')
            ->update([
                'status' => 'active',
                'email_verified_at' => now(),
                'confirmation_token' => NULL // reset token
            ]);
        if(! $updated) {
            return Respond::error('INVALID_CONFIRMATION_TOKEN');
        }

        return Respond::ok('Email confirmed!');
    }
}
