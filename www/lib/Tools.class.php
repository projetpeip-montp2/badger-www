<?php
    class Tools
    {
        static public function conflict($lft, $rht)
        {
            $result;

            if(Date::compare($lft->getDate(), $rht->getDate()) == 0)
            {
                $t1 = $lft->getStartTime();
                $t2 = $lft->getEndTime();

                $t3 = $rht->getStartTime();
                $t4 = $rht->getEndTime();

                return (Time::compare($t4, $t1) == 1) && 
                       (Time::compare($t3, $t2) == -1);
            }
        
            return false;
        }
    }
?>
