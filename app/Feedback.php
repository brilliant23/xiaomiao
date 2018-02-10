<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = ['open_id', 'customer_content', 'reply_id', 'reply_content', 'status'];
}