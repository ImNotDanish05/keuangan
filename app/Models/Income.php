<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'amount', 'received_at', 'description'
    ];

    protected $casts = [
        'received_at' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('received_at', $year)->whereMonth('received_at', $month);
    }
}

