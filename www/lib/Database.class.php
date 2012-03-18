<?php

class MySQLIterator implements Iterator
{
	private $m_position;
	private $m_curResult;
	private $m_req;
	
	function __construct($req)
	{
		$this->m_position = 0;
		$this->m_req = $req;
	}
	
	function current()
	{
		return ($this->m_curResult);
	}
	
	function key()
	{
		return ($this->m_position);
	}
	
	function next()
	{
		$this->m_position++;
		$this->m_curResult = mysqli_fetch_assoc($this->m_req);
	}
	
	function rewind()
	{
		$this->m_position = 0;
		mysqli_data_seek($this->m_req, 0);
		$this->m_curResult = mysqli_fetch_assoc($this->m_req);
	}
	
	function valid()
	{
		return ((boolean) $this->m_curResult);
	}
}

class Statement implements IteratorAggregate
{
	private $m_link;
	private $m_id;
	private $m_req;
	
	function __construct($link, $id, $req = null)
	{
		$this->m_link = $link;
		$this->m_id = $id;
		$this->m_req = $req;
	}
	
	function execute($opts)
	{
		$varList = '';
		
		while (list($key, $val) = each($opts))
		{
			$val = mysqli_real_escape_string($this->m_link, $val);

			if (mysqli_query($this->m_link, "SET @$key='$val'") === FALSE)
				throw new RuntimeException('Cannot assign variables for prepared request');

			if (empty($varList))
				$varList = "@$key";
			else
				$varList .= ", @$key";
		}

		$this->m_req = mysqli_query($this->m_link, "EXECUTE {$this->m_id} USING $varList");

		if ($this->m_req === FALSE)
			throw new RuntimeException('Execution of prepared request failed');
	}
	
	function fetch()
	{
		return (mysqli_fetch_assoc($this->m_req));
	}
	
	function fetchAll()
	{
		$result = array();
		
		while(($data = $this->fetch()) !== NULL)
			$result []= $data;

		return ($result);
	}
	
	function getIterator()
	{
		return new MySQLIterator($this->m_req);
	}
}

class Database
{
	private $m_link;

	function __construct($host, $username, $password, $dbName)
	{	
		$this->m_link = mysqli_connect($host, $username, $password);

		if ($this->m_link === FALSE)
			throw new RuntimeException('Cannot connect to MySQL');

		if (mysqli_select_db($this->m_link, $dbName) === FALSE)
			throw new RuntimeException('Cannot select database');
	}
	
	function __destruct()
	{
		mysqli_close($this->m_link);
	}
	
	function query($request)
	{
		$req = mysqli_query($this->m_link, $request);
		
		if ($req === FALSE)
			throw new RuntimeException('Cannot execute request');
			
		$stmt = new Statement($this->m_link, null, $req);
		
		return ($stmt);
	}
	
	function prepare($preparedRequest)
	{
		$id = uniqid();

		if (mysqli_query($this->m_link, "PREPARE $id FROM '$preparedRequest'") === FALSE)
			throw new RuntimeException('Cannot prepare request');

		$stmt = new Statement($this->m_link, $id);

		return ($stmt);
	}
}

?>

