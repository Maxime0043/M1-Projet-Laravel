<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $table = 'chapters';
    protected $dates = ['duration'];
    public $timestamps = false;

    protected $fillable = ['title', 'duration', 'content', 'formation'];

    public function formatedDuration()
    {
        list($hours, $minutes) = explode(':', $this->duration->format('G:i'));

        $hours = intval($hours);
        $minutes = intval($minutes);

        if ($hours == 0)
            $time = $minutes . ' min';
        else
            $time = $hours . 'h' . ($minutes < 10 ? "0$minutes" : $minutes);

        return $time;
    }

    public function pictures()
    {
        return $this->hasMany(ChapterPicture::class, 'chapter', 'id');
    }
}
