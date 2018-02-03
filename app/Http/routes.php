<?php

error_reporting(E_ALL & ~E_NOTICE);

$component_path = app_path() . DIRECTORY_SEPARATOR . "Components";

if (\File::isDirectory($component_path)){

    $list = \File::directories($component_path);

	
    foreach($list as $module){
		
		if (\File::isDirectory($module)){
            if(\File::isFile($module . DIRECTORY_SEPARATOR . "routes.php")){
                require_once($module . DIRECTORY_SEPARATOR . "routes.php");
            }
        }
    }
}
