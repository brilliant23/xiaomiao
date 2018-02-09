<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dept extends Model
{
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];
}