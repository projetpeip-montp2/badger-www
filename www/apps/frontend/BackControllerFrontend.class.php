<?php
    ////////////////////////////////////////////////////////////
    /// \class BackControllerFrontend
    ///
    /// \brief
    /// A BackControllerFrontend is an abstract class that inherits
    /// from the BackController.
    /// It is meant to be derivated into concrete controllers
    ///to handle specifically each part of Frontend.
    /// The BackControllerFrontend specifies some information
    /// that are only displayed on the Frontend layout.
    ////////////////////////////////////////////////////////////
    abstract class BackControllerFrontend extends BackController
    {
        protected $m_TEXT;

        ////////////////////////////////////////////////////////////
        /// \function __construct
        ///
        /// \brief
        /// Default constructor of the BackController class
        /// Initializes the variables of the class
        ///
        /// \param app Reference to the Application
        /// \param module Name of the module
        /// \param action Name of the action
        ////////////////////////////////////////////////////////////
        public function __construct(Application $app, $module, $action)
        {
            parent::__construct($app, $module, $action);

            // Include langage file for the view
            require dirname(__FILE__).'/lang/' . $this->app()->user()->getAttribute('vbmifareLang') . '.php';
            $this->m_TEXT = $TEXT;
        }

        ////////////////////////////////////////////////////////////
        /// \function getInfos
        ///
        /// \brief
        /// Creates view variables for information about subscriptions
        /// to packages and the limit date
        ////////////////////////////////////////////////////////////
        public function getInfos()
        {
            $limitDate = new Date;
            $limitDate->setFromMySQLResult($this->m_managers->getManagerOf('config')->get('registrationsDateLimit'));

            $currentDate = Date::current();

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
