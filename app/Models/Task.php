<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These match the columns from your coding challenge.
     */
    protected $fillable = [
        'title',      // [cite: 19]
        'due_date',   // [cite: 22]
        'priority',   // [cite: 25]
        'status',     // [cite: 28]
    ];
}