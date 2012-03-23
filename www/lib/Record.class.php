<?php
    abstract class Record
    {   
        public function hydrate($data)
        {
		    foreach ($data as $key => $value)
		    {
                $method = 'set'.ucfirst($key);

                if (!is_callable(array($this, $method)))
                    throw new RuntimeException('The function ' . $method . ' is not defined in this class');

                $this->$method($value);
		    }
        }
    } 
?>

