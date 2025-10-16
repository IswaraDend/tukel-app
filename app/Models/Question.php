<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Question extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['assignment_id', 'question_index', 'question_text', 'weight'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}
