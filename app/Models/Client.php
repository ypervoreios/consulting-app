<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'company_name',
        'email',
        'phone',
    ];

    public function projects()
{
    return $this->hasMany(Project::class);
}
}
