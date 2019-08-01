<?php namespace System;
/*
 * Base model used to connect to db
 *
 */

use App\Config;
use App\Helpers\Database;

class BaseModel
{
	/**
	 * hold the database connection to be used by other models
	 * @var object
	 */
	protected $db;

	/**
	 * create a new instance of the database helper
	 */
	public function __construct() {

		//initiate config
		$config = Config::get();

		//connect to PDO here.
		$this->db = Database::get($config);
	}
}

 /* CREDIT TO: "Beginning PHP" by David Carr and Markus Gray */