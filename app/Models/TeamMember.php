<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TeamMember extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['team_id', 'member_name', 'student_number', 'member_email'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'member_id');
    }
}
