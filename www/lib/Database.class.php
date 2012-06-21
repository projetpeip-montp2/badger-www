<?php
    class MySQLIterator implements Iterator
    {
	    private $m_position;
	    private $m_curResult;
	    private $m_req;
	
	    public function __construct($req)
	    {
		    $this->m_position = 0;
		    $this->m_req = $req;
	    }
	
	    public function current()
	    {
		    return ($this->m_curResult);
	    }
	
	    public function key()
	    {
		    return ($this->m_position);
	    }
	
	    public function next()
	    {
		    $this->m_position++;
		    $this->m_curResult = mysqli_fetch_assoc($this->m_req);
	    }
	
	    public function rewind()
	    {
		    $this->m_position = 0;
		    mysqli_data_seek($this->m_req, 0);
		    $this->m_curResult = mysqli_fetch_assoc($this->m_req);
	    }
	
	    public function valid()
	    {
		    return ((boolean) $this->m_curResult);
	    }
    }



    class Statement implements IteratorAggregate
    {
	    private $m_link;
	    private $m_id;
	    private $m_req;
	
	    public function __construct($link, $id, $req = null)
	    {
		    $this->m_link = $link;
		    $this->m_id = $id;
		    $this->m_req = $req;
	    }
	
	    public function execute($opts = array())
	    {
		    $varList = '';
			
		    while(list($key, $val) = each($opts))
		    {
			    $val = mysqli_real_escape_string($this->m_link, $val);

			    if(mysqli_query($this->m_link, "SET @$key='$val'") === FALSE)
				    throw new RuntimeException('Cannot assign variables for prepared request');

			    if(empty($varList))
				    $varList = "@$key";
			    else
				    $varList .= ", @$key";
		    }

			if(!empty($opts))
				$this->m_req = mysqli_query($this->m_link, "EXECUTE {$this->m_id} USING $varList");
			else
				$this->m_req = mysqli_query($this->m_link, "EXECUTE {$this->m_id}");
				
		    if($this->m_req === FALSE)
			    throw new RuntimeException('Execution of prepared request failed: '. mysqli_error($this->m_link));
	    }
	
	    public function fetch()
	    {
		    return (mysqli_fetch_assoc($this->m_req));
	    }
	
	    public function fetchAll()
	    {
		    $result = array();
		
		    while(($data = $this->fetch()) !== NULL)
			    $result []= $data;

		    return ($result);
	    }
	
	    public function getIterator()
	    {
		    return new MySQLIterator($this->m_req);
	    }
		
		public function getNumRows()
		{
			$num_rows = mysqli_num_rows($this->m_req);
			
			if ($num_rows === FALSE)
				throw new RuntimeException("Cannot retrieve number of rows");
				
			return ($num_rows);
		}
    }



    class Database
    {
	    private $m_link;

	    public function __construct($host, $username, $password, $dbName, $port = 3306)
	    {	
		    $this->m_link = mysqli_connect($host, $username, $password, $dbName, $port);

		    if($this->m_link === FALSE)
			    throw new RuntimeException('Cannot connect to MySQL');
	    }
	
	    public function __destruct()
	    {
		    mysqli_close($this->m_link);
	    }
	
	    public function query($request)
	    {
		    $req = mysqli_query($this->m_link, $request);
		
		    if($req === FALSE)
			    throw new RuntimeException('Cannot execute request "' . mysqli_error($this->m_link) . '"');
			
		    $stmt = new Statement($this->m_link, null, $req);
		
		    return ($stmt);
	    }
	
	    public function prepare($preparedRequest)
	    {
		    $id = uniqid();

		    if(mysqli_query($this->m_link, "PREPARE $id FROM '$preparedRequest'") == FALSE)
			    throw new RuntimeException('Cannot prepare request "' . mysqli_error($this->m_link) . '"');

		    $stmt = new Statement($this->m_link, $id);

		    return ($stmt);
	    }

	    public function lastInsertId()
	    {
            $id = mysqli_insert_id($this->m_link);

		    if($id == 0)
			    throw new RuntimeException('Cannot retrieve the last insert id');

            return $id;
	    }
    }
?>
