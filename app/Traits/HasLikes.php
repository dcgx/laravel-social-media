<?php

namespace App\Traits;

use App\Models\Like;
use App\Events\ModelLiked;
use App\Events\ModelUnliked;

use Illuminate\Support\Str;

trait HasLikes
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like() // Ejecuta el like 
    {
        $this->likes()->firstOrCreate([
            'user_id' => auth()->id()
        ]);

        ModelLiked::dispatch($this, auth()->user()); //En un trait, $this hace referencia al modelo que utiliza este trait
    }

    public function isLiked()
    {   
        return $this->likes()->where('user_id', auth()->id())->exists(); //nos interesa saber si existe en bd
    }

    public function unlike()
    {
        $this->likes()->where([
            'user_id' => auth()->id()
        ])->delete();

        ModelUnliked::dispatch($this);
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function eventChannelName()
    {
        return Str::lower(Str::plural(class_basename($this))) . "." . $this->getKey() . ".likes"; //chanelnames.1.likes
    }

    abstract public function path();
}
