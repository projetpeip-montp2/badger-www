<?php
    // TODO: Sortir les connections à la base de donnée de cette classe

    class Config extends ApplicationComponent
    {
        protected $m_vars = array();

        public function load($force)
        {
            if (!$this->m_vars || $force)
            {
                $dao = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

                $req = $dao->query('SELECT * FROM Config');
                
                foreach($req->fetchAll() as $elem)
                    $this->m_vars[$elem['Name']] = $elem['Value'];
            }
        }

        public function get($var)
        {
            $this->load(false);
            
            if(!isset($this->m_vars[$var]))
                throw new RuntimeException('The config variable "'. $var .'" does not exist.');

            return $this->m_vars[$var];
        }

        public function replace($key, $value)
        {
            $dao = new Database('localhost', 'vbMifare', 'vbMifare2012', 'vbMifare');

            $req = $dao->prepare('UPDATE Config SET Value = ? WHERE Name = ?');
            $req->execute(array($value, $key));

            $this->load(true);
        }
    } 
?>
