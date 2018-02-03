<?php

namespace App\Components\Client\Modules\Home;

use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
	protected $table = 'setting';

	protected $fillable = ['google_analytic', 'container_color', 'theme_color' , 'background_color', 'font_family', 'font_color', 'company_info_img'];
}