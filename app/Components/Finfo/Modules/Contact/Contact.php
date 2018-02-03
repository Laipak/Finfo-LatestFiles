<?php

namespace App\Components\Finfo\Modules\Contact;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contact_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'subject', 'message'];
}
