<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telegram_chat_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Saham yang dipilih user untuk mendapatkan
     * seluruh notifikasi stock signal.
     */
    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(
            Stock::class,
            'user_stocks',
            'user_id',
            'stock_code',
            'id',
            'stock_code'
        )->withTimestamps();
    }


    /**
     * Digunakan oleh Telegram notification channel.
     */
    public function routeNotificationForTelegram(): ?string
    {
        return $this->telegram_chat_id;
    }
}
