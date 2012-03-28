<?php
    class ResetManager extends Manager
    {
        public function truncate(array $tables)
        {
            $num = count($tables);

            if($num == 0)
                throw new InvalidArgumentException('No tables in arguments, need at least one.');

            // TODO: Est-il possible d'utiliser '?' pour spécifier le nom d'une table?
            // Au moins on pourrait préparer la requête.

            for ($i=0; $i<$num; $i++)
                $this->m_dao->query('TRUNCATE TABLE ' . $tables[$i]);
        }
    }
?>
