<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'amount', 'spent_at', 'description'
    ];

    protected $casts = [
        'spent_at' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('spent_at', $year)->whereMonth('spent_at', $month);
    }
}

