<?php


namespace App\Helpers;


use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;

class HashEmail
{

    /**
     * Encrypt email to secure hash.
     *
     * @param $email
     * @return string
     */
    public static function encrypt ($email): string
    {
        return Crypt::encryptString($email);
    }


    /**
     * Decrypt hash to original string.
     *
     * @param $hash
     * @return JsonResponse|string
     */
    public static function decrypt ($hash): JsonResponse|string
    {
        try {
            $decryptedHash = Crypt::decryptString($hash);
        } catch (DecryptException $e) {
            return Respond::error('INVALID_HASH');
        }

        // Validate Email
        if (!filter_var($decryptedHash, FILTER_VALIDATE_EMAIL)) {
            return Respond::error('INVALID_HASH');
        }

        return $decryptedHash;
    }
}
