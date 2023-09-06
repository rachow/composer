<?php
/*
 *	@author: rachow
 *	@copyright: 2018
 *	@package: Cli
 */
namespace Composer;

class Cli
{
	/**
 	* @var - current access mode
 	*/
	protected $mode;

	/**
 	* @var - 
 	*/
	protected $exit_mode;

	/**
 	* Creates an instance.
  	* @param void
	*/
	public function __construct()
	{
		if (php_sapi_name() !== "cli" ) {
			$this->mode = 'http';
			$this->exit_mode = true;

			// only support CLI for now
			$this->show_message('ERROR: Access through command line only!');
		}
		
		$this->mode = 'cli';
		$this->exit_mode = false;
		$cli_intro  = "\r\n==========================================================\r\n";
		$cli_intro .= "\t\tWelcome to the Installer\t\t\t";
		$cli_intro .= "\r\n==========================================================\r\n";
		echo $cli_intro;
		sleep(1);
		$cli_intro = "\t\tPlease wait a few seconds ...\r\n\r\n";
		echo $cli_intro;
		flush();
		sleep(1);
	}
	
	/**
 	* Buffer the message to terminal or browser.
  	* @param $msg | mixed
   	* @param $exit_mode | boolean
	*/
	public function show_message($msg, $exit_mode = false)
	{
		$this->exit_mode = $exit_mode;
		if ($this->mode == "cli" ) {
			$this->show_cli_message( $msg );
			return;
		} else {
			$this->show_http_message( $msg );
		}
	}

	/**
 	* Buffer message to the command line interface (CLI)
  	* @param $message | mixed
	*/
	protected function show_cli_message($message)
	{
		echo '[' . date('d-m-Y H:i:s') . '] ' . "\r\n" . $message . "\r\n";
		flush(); //flushing to cli mode
		if ($this->exit_mode == true) {
			exit;
		}
	}

	/**
 	* Buffer message as HTTP response.
  	* @param $message | mixed
	*/
	protected function show_http_message( $message )
	{
		header('HTTP/1.1 200 OK');
		echo $message;
		if ($this->exit_mode == true) {
			exit;
		}
	}
}
