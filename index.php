<?php
if(file_exists('vendor/autoload.php')){
	require 'vendor/autoload.php';

} else {
	echo "<p>Install Composer. Instructions: <a href='https://getcomposer.org/doc/00-intro.md#globally'>https://getcomposer.org/doc/00-intro.md#globally</a></p>";
	echo "<p>Once composer is installed navigate to the working directory in your terminal/command promt and enter 'composer install'</p>";
	exit;
}

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
define('APPDIR', realpath(__DIR__.'/app/') .DS);
define('SYSTEMDIR', realpath(__DIR__.'/system/') .DS);
define('PUBLICDIR', realpath(__DIR__) .DS);
define('ROOTDIR', realpath(__DIR__.'/') .DS);

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 * Different environments will set different levels of error reporting.
 * By default development will show errors but production will hide them.
 *
 * NOTE: When environment is changed, also change the error_reporting() 
 * code below.
 * Environment options are: development and production
 */
	define('ENVIRONMENT', 'development');
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */

if (defined('ENVIRONMENT')){

	switch (ENVIRONMENT){
		case 'development':
			error_reporting(E_ALL);
		break;

		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}

}

//initiate config
$config = App\Config::get();

new System\Route($config);

 /* CREDIT TO: "Beginning PHP" by David Carr and Markus Gray */
