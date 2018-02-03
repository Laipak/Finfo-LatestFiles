<?php
namespace App\Components\Package\Modules\MediaAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaAccessFiles extends Model  {
    use SoftDeletes;
    protected $table = 'media_access_files';
    protected $dates = ['deleted_at'];
}
