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
			$this->show_message('ERROR: Nothing to install type --help for more info!');
		}
				
	}
}

