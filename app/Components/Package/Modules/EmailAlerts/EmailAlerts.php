<?php

namespace App\Components\Package\Modules\EmailAlerts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailAlerts extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'email_alert';
    
    protected $fillable = ['is_delete'];


    protected $dates = ['deleted_at'];

}
