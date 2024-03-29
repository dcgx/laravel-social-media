<?php

namespace App\Models;

use App\Models\User;
use App\Models\Status;

use App\Traits\HasLikes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    use HasLikes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function status()
    {
        return $this->belongsTo(Status::class);
    } 

    public function path()
    {
        // return route('statuses.show', $this->status_id . '#comment-' . $this->id);

        return 'estado/' . $this->status_id . '#comment-' . $this->id;
    }
}
