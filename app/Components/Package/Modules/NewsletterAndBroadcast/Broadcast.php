<?php

namespace App\Components\Package\Modules\NewsletterAndBroadcast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Broadcast extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'broadcast_setting';

    protected $fillable = ['is_delete'];


    protected $dates = ['deleted_at'];

}
