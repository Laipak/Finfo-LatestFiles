<?php

namespace App\Components\Package\Modules\Presentation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presentation extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'presentation';

    protected $fillable = ['is_delete'];


    protected $dates = ['deleted_at'];

}
