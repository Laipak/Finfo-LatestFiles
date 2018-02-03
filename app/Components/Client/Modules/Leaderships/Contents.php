<?php

namespace App\Components\Client\Modules\Leaderships;

use Illuminate\Database\Eloquent\Model;


class Contents extends Model
{
	protected $table = 'content';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'title', 'slug', 'content_description', 'is_active', 'ordering', 'created_by', 'updated_by'];

}