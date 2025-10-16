<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'owner_id', 'description', 'is_locked'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

}
