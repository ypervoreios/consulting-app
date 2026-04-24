<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'file_path',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
