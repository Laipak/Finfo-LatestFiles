<?php
namespace App\Components\Package\Modules\MediaAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MediaAccessDownload extends Model  {
    protected $table = 'media_access_download';
    public $timestamps = false;
}
