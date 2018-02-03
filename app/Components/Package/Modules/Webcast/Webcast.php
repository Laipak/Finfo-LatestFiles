<?php

namespace App\Components\Package\Modules\Webcast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Webcast extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'webcast';

    protected $dates = ['deleted_at'];

}
