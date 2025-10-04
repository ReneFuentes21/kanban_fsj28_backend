<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'board_id'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
