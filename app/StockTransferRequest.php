<?php
// app/Models/StockTransferRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'tr_no',
        'tr_date',
        'description',
        'status',
        'username',
        'user_id',
        'total_amount',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'tr_date' => 'date',
        'approved_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    // Relationship - FIX THE FOREIGN KEY
    public function items()
    {
        return $this->hasMany(StockTransferRequestItem::class, 'stock_transfer_request_id');
    }

    // Scopes for easy querying
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 2);
    }

    // Status labels
    public function getStatusTextAttribute()
    {
        return [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected'
        ][$this->status] ?? 'Unknown';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            0 => 'badge badge-warning',
            1 => 'badge badge-success',
            2 => 'badge badge-danger'
        ];
        
        return '<span class="'.$badges[$this->status].'">'.$this->status_text.'</span>';
    }
}