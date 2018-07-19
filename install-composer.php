<?php
/*
*   @author: rachow
*   @copyright: 2018
*
*   @file: install-composer.php
*       Installs the Com[poser] $$!
*       Other useful commands to do the following
*       -clean = cleans the unwanted files scattered
*       -reinstall
*       -hide
*/

error_reporting( E_ALL ^ E_NOTICE );
ini_set( 'display_errors' , 'off' );

$cwd = dirname( __FILE__ );
$pid = getmypid();
$fuid = md5(time());

chdir( $cwd ); /* change to the current directory! */

/* fetch the user argument if any! */
$command = @$argv[1];
$command_second = @$argv[2];
//extra options

//for installing laravel
$install_laravel = false;

if( php_sapi_name() !== "cli")
{
   die("Direct access not allowed");
}



/*
***************************************************************************************
*                   Run through Command Modes
***************************************************************************************
*/

if( $command == '--clean' )
{
    //cleaning in process!
    echo "\r\n$pid: Cleaning now ....\r\n";
    flush();
    unlink( dirname( __FILE__ ) . '/sha-signature-check.dat' );
    $delete_mask = 'composer-setup-*.php';
    array_map( 'unlink', glob( $delete_mask ) );
    unlink( dirname( __FILE__ ) . '/composer-setup-*.php' );
    rename('install-composer.php','.install-composer.php');
    echo "\r\nDone - clean complete\r\n";
    exit;

}elseif( $command == '--reinstall' )
{
    unlink( dirname( __FILE__ ) . '/composer.phar' );
    //continue as normal install now..

    if( file_exists( dirname( __FILE__ ) . '/composer.json' ) || file_exists( dirname( __FILE__ ) . '/composer.lock' ) )
    {
        echo "\r\n$pid: WARNING - files 'composer.json' and/or 'composer.lock' exist, you must remove these from here before re-installing!\r\n";
        exit;
    }

}elseif( $command == '--help' )
{
    echo "\r\n--help = shows help\r\n";
    echo "\r\n--clean = cleans unwanted files\r\n";
    echo "\r\n--reinstall = re-installs the composer even if it exists\r\n";
    echo "\r\n--hide = hides the installer file to hidden\r\n";
    echo "\r\n--laravel = installs composer then installs laravel in current directory\r\n";
    exit;
    
}elseif( $command == '--hide' )
{
    echo "\r\nHiding myself to .install-composer.php\r\n";
    echo "\r\nDone\r\n";
    flush();
    rename( dirname( __FILE__ ) . '/install-composer.php' , dirname( __FILE__ ) . '/.install-composer.php' );
    exit;
}elseif( $command == '--delete' )
{
	echo "\r\nDeleting the installation file: " . __FILE__  . "\r\n";
	echo "\r\nDone\r\n";
	unlink( __FILE__ );
	exit;
}elseif( $command == '--laravel' )
{
    $install_laravel = true;
}
elseif( isset( $command ) && !empty( $command ) )
{
    echo "\r\nCommand not recognized enter --help for manual\r\n";
    flush();
    exit;
}

/*
***************************************************************************************
*                   Install Composer Now
***************************************************************************************
*/

if( file_exists( dirname( __FILE__ ) . '/composer.phar' ) )
{
    exit( "\r\n$pid: Composer Already Installed - composer.phar exists!\r\n" );
}

if( file_exists( dirname( __FILE__ ) . '/sha-signature-check.dat' ) )
{
    echo "\r\n$pid: Removing the existing sha-signature-check.dat file!\r\n";
    flush(); //flush it to the user!
}

if( !file_exists( dirname( __FILE__) . '/composer-setup-' . $fuid . '.php' ) )
{
    if( !copy( 'https://getcomposer.org/installer', dirname( __FILE__ ) . '/composer-setup-' . $fuid . '.php' ) )
    {
        exit( "\r\n$pid: Could not install Composer\r\n" );
    }
}

if( !file_exists( dirname( __FILE__ ) . '/composer-setup-' . $fuid . '.php' ) )
{
    exit( "\r\n$pid: Could not install Composer file - composer-setup-$fuid.php does not exist\r\n" );
}

//retrieve the sha!
if( !copy( 'https://composer.github.io/installer.sig', dirname( __FILE__ ) . '/sha-signature-check.dat' ) )
{
    exit( "\r\n$pid: Could not write the SHA locally to verify\r\n" );
}

//sleep for 1 second!
echo "\r\n$pid: Sleeping for little bit\r\n";
flush();
sleep(1); /* flushed the content here */

if( !file_exists( dirname( __FILE__ ) . '/sha-signature-check.dat' ) )
{
    exit( "\r\n$pid: Could not perform the SHA check, try again if you must!\r\n" );
}

$sha = file( dirname( __FILE__ ) . '/sha-signature-check.dat', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
$sha_composer = hash_file( 'SHA384', dirname( __FILE__ ) . '/composer-setup-' . $fuid . '.php' );

echo "\r\n$pid: SHA: $sha[0]\r\n";
echo "\r\n$pid: FILE SHA: $sha_composer\r\n";
echo "\r\n$pid: Makesure to run this file again with the command --clean\r\n";
flush();

if( !( $sha_composer == $sha[0] ) )
{
    echo "\r\n$pid: Installer is not verified and may be corrupt!!\r\n";
    unlink( dirname( __FILE__ ) . '/composer-setup-' . $fuid . '.php' );
    unlink( dirname( __FILE__ ) . '/sha-signature-check.dat' );
    exit( "\r\n$pid: Done\r\n" );
}

//creating the composer.json file now....
/*
$JSON_DEPENDENCY = <<<_JSDEPEND_
{
    "require": {
        "monolog/monolog": "1.0.*"
    }
}
_JSDEPEND_;
 */
//Kepp non dependencies for now to start with
$JSON_DEPENDENCY = <<<_JSDEPEND_
{
    "": {
        "": ""
    }
}
_JSDEPEND_;

if( !file_exists( dirname( __FILE__ ) . '/composer.json' ) )
{
	file_put_contents( dirname( __FILE__ ) . '/composer.json', $JSON_DEPENDENCY );
	echo "\r\n$pid: composer.json file created\r\n";
	flush();
}

//run it...
//include dirname( __FILE__ ) . '/composer-setup-' . $fuid . '.php';
$cmd = 'php composer-setup-' . $fuid . '.php';
$composer_installed = shell_exec($cmd);

unlink( dirname( __FILE__ ) . '/composer-setup-' . $fuid . '.php' );
unlink( dirname( __FILE__ ) . '/sha-signature-check.dat' );

echo "\r\n$pid: Composer now Installed\r\n";

if( $install_laravel == true )
{
    //okay we need to install laravel now
    echo "\r\nInstalling Laravel\r\n";
    flush();
    $path = dirname(__FILE__) . '/';
    $cmd = 'php ' . $path . 'composer.phar require laravel/installer';
    $return = shell_exec($cmd);
    $create_laravel_dir = isset( $command_second ) && !empty( $command_second ) ? $command_second : 'public';
    $cmd = 'php ' . $path  . 'vendor/laravel/installer/laravel new ' . $create_laravel_dir;
    $return = shell_exec($cmd);
}

exit( "\r\n$pid: Done\r\n" );

?>
