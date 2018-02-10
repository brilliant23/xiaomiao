<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intend extends Model
{
    protected $table = 'intends';
    protected $fillable = ['name', 'phone', 'intentions', 'reply_id'];
}
