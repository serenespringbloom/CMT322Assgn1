<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    protected $table = 'refund_requests';

    protected $primaryKey = 'refund_id';

    protected $fillable = [
        'transaction_id',
        'reason',
        'status',
        'processed_by',
        'processing_comments',
    ];
}
