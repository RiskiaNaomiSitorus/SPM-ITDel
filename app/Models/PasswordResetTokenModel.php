<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetTokenModel extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';

    public $incrementing =  false;
    protected $fillable = [
        'email',
        'token',
        'pass_test',
        'updated_at'
    ];
}
