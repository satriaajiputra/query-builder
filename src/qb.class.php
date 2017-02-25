<?php

/**
* Query Bulder With PDO
*/
class QueryBuilder
{
	protected	$host 		= 'localhost',
			$dbname 	= 'itclub',
			$username 	= 'root',
			$password 	= '()Sat828';

	private static $_instance = null; // static initiate connection

	private $_pdo, $_table, $_query, $_open, $_columns = '*',
			$_statement, $_wheres, $_limit, $_params = [], $_order;

	/**
	 *
	 * Construct with making db conn
	 * @return array
	 */
	function __construct()
	{
		try {
			$this->_pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die("Error: ".$e->getMessage());
		}
	}

	/**
	 *
	 * Selet table that you need
	 * @param string
	 * @return this
	 */
	public function setTable($table)
	{
		$this->_table = $table;
		return $this;
	}

	/**
	 *
	 * Select column or all columns
	 * @param string
	 * @return this
	 */
	public function select($columns = '*')
	{
		$this->_query = "SELECT $columns FROM $this->_table";
		$this->_columns = $columns;	
		return $this;
	}

	/**
	 *
	 * Create new record in database table
	 * @param array
	 * @return this
	 */
	public function create($data = array())
	{
		$column = '';
		$param = '';

		$column = implode(',', array_keys($data));

		foreach ($data as $value) {
			$this->_params[] = $value;
			$param .= '?,';
		}

		$param = substr($param, 0, -1);

		$this->_query = "INSERT INTO $this->_table (".$column.") VALUES (".$param.")";
		$this->run();
		return $this;
	}

	/**
	 *
	 * Update a record data in database
	 * @param array
	 * @return this
	 */
	
	public function update($data = array())
	{
		$sortQuery = '';
		foreach ($data as $key => $value) {
			$this->_params[] = $value;
			$sortQuery .= "$key = ?, ";
		}
		$sortQuery = substr($sortQuery, 0, -2)." ";
		
		$this->_query = "UPDATE $this->_table SET ".$sortQuery;
		return $this;
	}

	/**
	 *
	 * Delete record by id or by some column
	 * @return this
	 */
	public function delete()
	{
		$this->_query = "DELETE FROM $this->_table";
		$this->run();
		return $this;
	}

	/**
	 *
	 * Save action for update recorded rows
	 * @return this
	 */
	public function save()
	{
		$this->run();
		return $this;
	}

	/**
	 *
	 * Set limitation when you selecting rows
	 * @param int
	 * @return this
	 */
	public function limit($limit)
	{
		$this->_limit = " LIMIT $limit";
		return $this;
	}

	/**
	 *
	 * Short the rows when selecting table and get it
	 * @param string
	 * @return this
	 */
	
	public function orderBy($col, $mode)
	{
		$this->_order .= " ORDER BY $col $mode";
		return $this;
	}

	/**
	 *
	 * Where clause function with bridges
	 * @param string
	 * @return this
	 */
	public function where($col, $sign, $value, $bridge = '')
	{
		if(empty($this->_wheres)) {
			$this->_wheres .= " WHERE ".$col." ".$sign." ? ".$bridge;
		} else {
			$this->_wheres .= " ".$col." ".$sign." ? ".$bridge;
		}
		$this->_params[] = $value;
		return $this;
	}

	/**
	 *
	 * Select all datas in a table
	 * @return object
	 */
	public function all()
	{
		$this->run();
		return $this->_statement->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 *
	 * Return amount rows data in a table
	 * @return int
	 */
	public function count()
	{
		$this->run();
		return $this->_statement->rowCount();
	}

	/**
	 *
	 * Return first record of rows in a table
	 * @return object
	 */
	public function first()
	{
		$this->run();
		return $this->_statement->fetch(PDO::FETCH_OBJ);
	}

	/**
	 *
	 * Controll all queries in this class
	 * @return string, array
	 */
	public function run()
	{
		// echo $this->_query.$this->_wheres.$this->_limit."<br>"; // debug
		try {
			$this->_statement = $this->_pdo->prepare($this->_query.$this->_wheres.$this->_order.$this->_limit);
			$this->_statement->execute($this->_params);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 *
	 * Static function for initiate database connection
	 * @return array
	 */
	public static function startConnection()
	{
		if(!isset(self::$_instance)) {
			self::$_instance = new QueryBuilder();
		}

		return self::$_instance;
	}
}
