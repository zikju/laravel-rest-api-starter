<?php

namespace App\Http\Controllers;

use App\Helpers\Respond;
use App\Http\Requests\Auth\PasswordRecovery\ChangePasswordRequest;
use App\Http\Requests\Auth\PasswordRecovery\SendConfirmEmailRequest;
use App\Mail\RecoveryConfirmEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Str;

class PasswordRecoveryController extends Controller
{
    /**
     * Send confirmation email with recovery link.
     *
     * @param SendConfirmEmailRequest $request
     * @return JsonResponse
     */
    public function sendConfirmationEmail (SendConfirmEmailRequest $request): JsonResponse
    {
        // Retrieve the validated input data...
        $validatedRequestData = $request->validated();
        $email = $validatedRequestData['email'];

        $confirmationToken = Str::uuid();

        $user = User::where('email', $email)
            ->where('status', 'active')
            ->update(['confirmation_token' => $confirmationToken]);
        if (! $user) {
            Respond::error("Account doesn't exist or not active");
        }


        // Send confirmation link
        Mail::to($email)->send(new RecoveryConfirmEmail($confirmationToken));

        return Respond::ok(
            'Recovery email has been sent to ' . $email,
            ['token' => $confirmationToken]
        );
    }



    /**
     * Change password.
     *
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword (ChangePasswordRequest $request): JsonResponse
    {
        // Retrieve the validated input data...
        $validatedRequestData = $request->validated();
        $newPassword = $validatedRequestData['password'];
        $confirmationToken = $validatedRequestData['token'];

        // Set user status to 'active'
        $updated = User::query()
            ->where('confirmation_token', $confirmationToken)
            ->where('status', '=', 'active')
            ->update([
                'password' => bcrypt($newPassword),
                'confirmation_token' => NULL // reset token
            ]);
        if(! $updated) {
            Respond::error("Account doesn't exist or not active");
        }

        return Respond::ok('Password successfully changed!');
    }
}
