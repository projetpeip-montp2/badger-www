<?php
    class ResetManager extends Manager
    {
        public function truncate(array $tables)
        {
            $num = count($tables);

            if($num == 0)
                throw new InvalidArgumentException('No tables in arguments, need at least one.');

            for ($i=0; $i<$num; $i++)
                $this->m_dao->query('DELETE FROM ' . $tables[$i]);
        }
    }
?>
