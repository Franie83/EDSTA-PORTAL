<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StaffMember extends Model
{
    protected $fillable = ['name', 'role', 'department', 'bio', 'email', 'image_url', 'sort_order'];
}