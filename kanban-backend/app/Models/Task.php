<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable = [
        'taskName',
        'description',
        'startDate',
        'endDate',
        'allocator',
        'employee',
        'priority',
        'progress',
        'card_id'
    ];
    
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
