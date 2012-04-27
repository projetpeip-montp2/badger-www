<?php
    class AjaxInput extends Record
    {
		private $m_data;
		private $m_value;
		private $m_type;
		
		public function __construct()
		{
			$this->m_value = '';
			$this->m_data = array();
			$this->m_type = '';
		}
		
		public function setValue($value)
		{
			$this->m_value = $value;
		}
		
		public function getValue()
		{
			return ($this->m_value);
		}
		
		public function setData($key, $value)
		{
			$this->m_data[$key] = $value;
		}
		
		public function getData($key)
		{
			if (array_key_exists($key, $this->m_data))
				return ($this->m_data[$key]);
			return null;
		}
	}
	
?>