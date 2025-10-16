<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ExportLog extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = ['assignment_id', 'exported_by', 'file_path', 'format', 'created_at'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'exported_by');
    }

}
