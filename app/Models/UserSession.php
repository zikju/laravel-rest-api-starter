<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * App\Models\UserSession
 *
 * @mixin \Eloquent
 */
class UserSession extends Model
{
    use HasFactory;

    protected $table = 'users_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'refresh_token',
        'expires_at',
        'ip_address'
    ];

    protected $visible = [
        'id',
        'user_id',
        'refresh_token',
        'expires_at'
    ];


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create User session in Database
     *
     * @param int $user_id
     * @param string $ip_address
     * @return string
     */
    public function createSession(int $user_id, string $ip_address): string
    {
        $refreshToken = Str::uuid()->toString();
        $expiresAt = $this->getRefreshTokenTTL();
        $this->query()->create([
            'user_id' => $user_id,
            'refresh_token' => $refreshToken,
            'expires_at' => $expiresAt,
            'ip_address' => $ip_address
        ]);

        return $refreshToken;
    }

    /**
     *
     * @return Carbon
     */
    private function getRefreshTokenTTL(): Carbon
    {
        $refreshTokenTTL = (int) env('JWT_REFRESH_TOKEN_TTL', 120);
        return Carbon::now()->addMinutes($refreshTokenTTL);
    }
}
