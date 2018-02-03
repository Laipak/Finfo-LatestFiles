<?php

namespace App\Components\Package\Modules\InvestorRelationsCalendar;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestorRelationsCalendar extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'investor_relations_calendar';

    protected $dates = ['deleted_at'];

}
