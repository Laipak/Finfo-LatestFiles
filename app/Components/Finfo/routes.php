<?php

$component_path = app_path() . DIRECTORY_SEPARATOR . "Components";
$modules = $component_path . DIRECTORY_SEPARATOR . "Finfo/Modules";

if (\File::isDirectory($modules)){

    $userDomainName = explode('.', Request::server('HTTP_HOST'));
    if (count($userDomainName) === 2 || count($userDomainName) === 4) {
        $list = File::directories($modules);

        foreach($list as $module){
            if (\File::isDirectory($module)){
                if(\File::isFile($module. DIRECTORY_SEPARATOR . "routes.php")){
                    require_once($module. DIRECTORY_SEPARATOR. "routes.php");
                }
            }
        }
    }
}
