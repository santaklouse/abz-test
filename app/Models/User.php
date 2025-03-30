<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class User extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasRelationships;

    const PHOTO_PATH = 'images/users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo',
        'position_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get the phone associated with the user.
     */
    public function position(): HasOne
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    /**
     * Get photo of the user.
     */
    public function photoUrl($full = TRUE): string
    {
        return $full ? Storage::disk('public')->url($this->photo) : Storage::url($this->photo);
    }

    /**
     * Get photo of the user.
     */
    public static function checkExists($email, $phone): string
    {
        return User::where('email', $email)->orWhere('phone', $phone)->exists();
    }

    /**
     * Get photo of the user.
     */
    public static function checkExistsById($id): string
    {
        return User::where('id', $id)->exists();
    }


}
