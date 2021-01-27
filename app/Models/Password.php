<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password',
        'user_id',
        'web_address',
        'description',
        'login',
    ];

    public function user()
    {
        return $this->BelongsTo(User::class, 'user_id', 'id');
    }
}
