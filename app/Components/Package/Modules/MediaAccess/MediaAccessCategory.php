<?php
namespace App\Components\Package\Modules\MediaAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaAccessCategory extends Model  {
    use SoftDeletes;
    protected $table = 'media_access_category';
    protected $dates = ['deleted_at'];
}
