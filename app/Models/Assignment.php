<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Assignment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'team_id', 'title', 'notes', 'tracking_enabled', 'is_archived', 'created_by'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
