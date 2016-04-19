<?php
namespace Comment\Libraries;

use DB;

class PgsqlDB extends DB
{
	protected $database = 'pgsql';
}