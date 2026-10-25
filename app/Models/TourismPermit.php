<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TourismPermit extends Model
{
    protected $fillable = ['user_id', 
        'certificate_number', 'business_name', 'business_type', 'owner_name', 
        'address', 'issue_date', 'expiry_date', 'amount_paid', 'status'
    ];
    
    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];
    
    public function isExpired()
    {
        return $this->expiry_date->isPast();
    }
}