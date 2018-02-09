<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillRecord extends Model
{
    protected $fillable = ['customer_id', 'money', 'type', 'info'];
}