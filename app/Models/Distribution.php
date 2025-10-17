<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Distribution extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'assignment_id',
        'question_id',
        'member_id',
        'status',
        'is_confirmed',
        'completed_at'
    ];

    protected $casts = [
        'is_confirmed' => 'boolean',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function member()
    {
        return $this->belongsTo(TeamMember::class, 'member_id');
    }
}
