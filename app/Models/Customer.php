<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'company_name',
        'address',
        'kvk_number',
        'contact_name',
        'contact_email',
        'contact_phone',
    ];
}
