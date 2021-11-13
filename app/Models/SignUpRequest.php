<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignUpRequest extends Model
{
    use HasFactory;

    protected $table = 'signup_requests';
    protected $fillable = ['email', 'lastname', 'firstname', 'picture'];
}
