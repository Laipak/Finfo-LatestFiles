<?php
namespace App\Components\Package\Modules\MediaAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class MediaAccess extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract {
    use Authenticatable, Authorizable, CanResetPassword;
    protected $table = 'media_access_users';
    public $timestamps = false;
    
    public function mediaAccessOrganization()
    {
        return $this->belongsTo('App\Components\Package\Modules\MediaAccess\MediaAccessOrganization');
    }
}
