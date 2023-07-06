<?php
//name of the site
define('SITE_NAME','VIEAT ADMIN LOGIN');
//name of the front page
define('FRONT_SITE_NAME','Welcome To VIEAT');

//server path
define('FRONT_SITE_PATH','http://127.0.0.1:7882/vieat_final/');
//image path
define('SERVER_IMAGE',$_SERVER['DOCUMENT_ROOT']." /vieat_final/");

//path wherein the dish gets stored/retrieved 
define('SERVER_DISH_IMAGE',SERVER_IMAGE."media/dish/");
define('SITE_DISH_IMAGE',FRONT_SITE_PATH."media/dish/");

define('SERVER_BANNER_IMAGE',SERVER_IMAGE."media/banner/");
define('SITE_BANNER_IMAGE',FRONT_SITE_PATH."media/banner/");

?>
