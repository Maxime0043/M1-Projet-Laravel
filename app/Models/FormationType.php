<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationType extends Model
{
    use HasFactory;

    protected $table = 'formations_types';
    public $timestamps = false;

    protected $fillable = ['formation', 'type'];
}
