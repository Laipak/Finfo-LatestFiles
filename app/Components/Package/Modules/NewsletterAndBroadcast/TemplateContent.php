<?php

namespace App\Components\Package\Modules\NewsletterAndBroadcast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateContent extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'newsletter_template_content';

    protected $fillable = ['is_delete'];


    protected $dates = ['deleted_at'];

}
