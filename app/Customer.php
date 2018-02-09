<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['company_name', 'corporation', 'phone', 'corporate_property', 'area', 'address_type', 'trade', 'credit_code', 'cooperate_time', 'get_business_time', 'revenue_time', 'account_id', 'sale_id', 'total_charge', 'sale_charge', 'last_charge', 'one_charge', 'created_by', 'updated_by'];
}

