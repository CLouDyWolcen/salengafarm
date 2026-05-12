<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'phone',
        'address',
        'message',
        'request_date',
        'due_date',
        'items_json',
        'pricing',
        'status',
        'request_type',
        'pdf_path',
        'response_sent_at',
        'responded_by'
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'due_date' => 'datetime',
        'response_sent_at' => 'datetime',
        'items_json' => 'array'
    ];
} 