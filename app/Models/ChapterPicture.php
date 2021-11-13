<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterPicture extends Model
{
    use HasFactory;

    protected $table = 'chapters_pictures';
    public $timestamps = false;

    protected $fillable = ['picture', 'chapter'];
}
