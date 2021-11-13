<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $table = 'formations';
    protected $fillable = ['title', 'description', 'price', 'picture', 'user_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'formation', 'id');
    }

    public function formatedTotalTime()
    {
        $minutes = 0;

        foreach ($this->chapters as $chapter) {
            list($hour, $minute) = explode(':', $chapter->duration->format('G:i'));

            $minutes += intval($hour) * 60;
            $minutes += intval($minute);
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;

        if ($hours == 0)
            $time = $minutes . ' min';
        else
            $time = $hours . 'h' . $minutes;

        return $time;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'formations_categories', 'formation', 'category');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'formations_types', 'formation', 'type');
    }
}
