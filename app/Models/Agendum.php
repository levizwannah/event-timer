<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendum extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'title',
        'duration',
        'order',
        'description',
        'started_at',
        'ended_at',
    ];

    public function casts() {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
