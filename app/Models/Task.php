<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'status',
        'due_date',
        'user_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
    return $this->belongsTo(User::class);
    }

    public function comments()
    {
    return $this->hasMany(Comment::class);
    }

}
