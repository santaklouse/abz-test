<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class AccessToken extends Model
{
    protected $fillable = ['token', 'expires_at', 'used_at'];

    protected $hidden = ['created_at', 'updated_at'];

    public function isExpired(): bool
    {
        return now() > $this->expires_at;
    }

    public function isUsed(): bool
    {
        return $this->used_at !== NULL;
    }

    public function makeUsed(): void
    {
        $this->used_at = now();
        $this->save();
    }

    public static function newToken(): string
    {
        $model = AccessToken::create([
            'token' => bin2hex(random_bytes(32)),
            'expires_at' => now()->addMinutes(40)
        ]);

        $model->token = Crypt::encrypt([
            'id' => $model->id
        ]);

        $model->save();

        return $model->token;
    }

    /**
     *
     * @param string $token
     * @return AccessToken|null
     */
    public static function findToken(string $token): ?AccessToken
    {
        $data = Crypt::decrypt($token);

        return AccessToken::find($data['id']);
    }

}
