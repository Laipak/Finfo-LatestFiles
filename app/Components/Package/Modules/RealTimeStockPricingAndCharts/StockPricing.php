<?php

namespace App\Components\Package\Modules\RealTimeStockPricingAndCharts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockPricing extends Model 
{
	use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */


    protected $table = 'stock_pricing_and_chart';

    protected $fillable = ['is_delete'];

    protected $dates = ['deleted_at'];

}
