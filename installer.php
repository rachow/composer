<?php
/*
 *	@author: rachow
 *	@copyright: 2018
 *	@package: Installer
 */

require_once __DIR__ . '/cli.php';

class Installer extends Cli
{
	protected $cwd;
	protected $pid;
	protected $fuid;
	protected $install;
	protected $installables;

	public function __construct()
	{
		parent::__construct();
		$this->cwd = dirname(__FILE__);
		$this->pid = getmypid();
		$this->fuid = md5(time());
		$this->install = [];
		$this->installables = [
			'composer','laravel','symfony','yii'
		];
	}

	protected function installable_package( $package )
	{
		return in_array( $package, $this->installables );
	}

	public function show_packages_installable()
	{
		$package_msg = "Below are the help options to choose from\r\n\r\n";
		foreach( $this->installables as $id => $package )
		{
			$package_msg .= '--' . $package . "\t" . 'will install this package' . "\r\n";
		}
		$this->show_message($package_msg);
	}

	public function install_package( $package )
	{
		if( !$this->installable_package( $package ) )
			$this->show_message('ERROR: Package ' . $package .' not supported by Installer for info type --help!');

		if( !isset($this->install[$package]) )
			$this->install[] = $package;
	}

	public function run()
	{
		if( empty( $this->install ) )
		{
			$this->show_message('ERROR: Nothing to install type --help for more info!', true);
		}

		foreach( $this->install as $key => $pckg )
		{
			$this->show_message('Ready to install ' . $pckg );

			switch( $pckg )
			{
				case 'composer':
					/* install composer */
					break;
				case 'laravel':
					/* install laravel check dependencies */
					break;
				case 'symfony':
					/* install symfony */
					break;
				case 'yii':
					/* install YII */
					break;
			}
		}
	}
}

