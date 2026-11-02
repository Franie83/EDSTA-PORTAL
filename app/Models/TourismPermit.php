<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourismPermit extends Model
{
    protected $fillable = [
        'user_id',
        'certificate_number',
        'facility_id',
        'facility_type',
        'business_name',
        'business_type',
        'owner_name',
        'address',
        'issue_date',
        'expiry_date',
        'amount_paid',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return true;
        }

        return $this->expiry_date->copy()->endOfDay()->isPast();
    }
}
