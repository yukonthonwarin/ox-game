<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['player_name','user_id', 'points', 'consecutive_wins'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
