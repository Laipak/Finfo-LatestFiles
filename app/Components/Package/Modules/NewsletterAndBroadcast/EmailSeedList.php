<?php

namespace App\Components\Package\Modules\NewsletterAndBroadcast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailSeedList extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'email_seed_list';

    protected $fillable = ['is_delete'];


    protected $dates = ['deleted_at'];

}
