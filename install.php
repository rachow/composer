<?php
/*
 *	@author: rachow
 *	@copyright: 2018
 *	@package: Installer
 */

require_once __DIR__ . '/installer.php';


/*
 * Usage: php installer.php --laravel
*/

$commands = @$argv;
$options = [];
$show_help = false;

foreach( $commands as $idx => $opt )
{
	if( $idx == 0 ) continue;
	if( $opt == '--help' ){
		$show_help = true;
		break;
	}
	$options[] = strtolower(trim(str_replace( '--', '' , $opt )));
}

$installer = new Installer();

if( $show_help == true )
{
	$installer->show_packages_installable();
	exit;
}


$cnt_packages = count($options);

if( $cnt_packages > 0 )
{
	for($j=0; $j < $cnt_packages; $j++)
	{
		$installer->install_package($options[$j]);
	}
}

/* now install all the packages */
$installer->run();
exit;


