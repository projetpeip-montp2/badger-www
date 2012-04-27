<?php
    abstract class BackControllerFrontend extends BackController
    {
        public function getInfos()
        {
            $limitDate = new Date;
            $limitDate->setFromString($this->m_managers->getManagerOf('config')->get('registrationsDateLimit'));
            $currentDate = new Date;
            $currentDate->setFromString(date('d-m-Y'));

            if(Date::compare($limitDate, $currentDate) > 0)
            {
                $this->page()->addVar('displayInfos', true);

                $this->page()->addVar('limitDate', $limitDate);

                $username = $this->app()->user()->getAttribute('logon');
                $registrations = $this->m_managers->getManagerOf('registration')->getRegistrationsFromUser($username);
                $count = $this->countSelectedPackages($registrations);
                $this->page()->addVar('packagesChosen', $count);
                $this->page()->addVar('packagesToChoose', $this->m_managers->getManagerOf('config')->get('packageRegistrationsCount'));
            }
            else
                $this->page()->addVar('displayInfos', false);
        }

        private function countSelectedPackages($registrations)
        {
            $existingPackages = array();
            foreach($registrations as $reg)
            {
                if(!in_array($reg->getIdPackage(), $existingPackages))
                    $existingPackages[] = $reg->getIdPackage();
            }

            return count($existingPackages);
        }
    } 
?>
