<?php

namespace App\Components\Client\Modules\Home;

use Illuminate\Database\Eloquent\Model;


class Theme extends Model
{
	protected $table = 'theme';

	protected $fillable = ['background_color', 'font_family', 'font_color', 'button_color'];
}