<?php

namespace App\Components\Package\Modules\PressReleases;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PressReleases extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'press_release';
    
    protected $fillable = ['is_delete'];


    protected $dates = ['deleted_at'];

}
