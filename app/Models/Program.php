<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'code', 'started_at', 'ended_at'];

    public function agenda()
    {
        return $this->hasMany(Agendum::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($program) {
            $program->code = strtoupper(Str::random(6));
        });
    }

    public function hasEnded() {
        return !empty($this->ended_at);
    }
}
