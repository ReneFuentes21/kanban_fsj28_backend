<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    //

    use HasFactory;
    
    protected $fillable = [
        'name',
        'numCards'
    ];

    protected $attributes = [
        'numCards' => 0
    ];

        public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
