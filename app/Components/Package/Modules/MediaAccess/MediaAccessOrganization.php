<?php
namespace App\Components\Package\Modules\MediaAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class MediaAccessOrganization extends Model {
    use SoftDeletes;
    protected $table = 'media_access_organization';
    protected $dates = ['deleted_at'];
    protected $fillable = ['is_delete'];
    
    public function mediaAccessUsers()
    {
        return $this->belongsTo('App\Components\Package\Modules\MediaAccess\MediaAccess',  'foreign_key');
    }
}
