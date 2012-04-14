<?php
    class ResetController extends BackController
    {
        // List of all tables in Database
        private static $m_tables = array('Answers',
                                         'AnswersOfUsers', 
                                         'Availabilities', 
                                         'BadgingInformations', 
                                         'Classrooms', 
                                         'DocumentsOfPackages', 
                                         'DocumentsOfUsers', 
                                         'Lectures', 
                                         'Packages', 
                                         'Questions',
                                         'QuestionsOfUsers',
                                         'Registrations',
                                         'Users');

        public function executeIndex(HTTPRequest $request)
        {

        }


        public function executeTruncate(HTTPRequest $request)
        {
            // If the form is submitted
            if($request->postExists('isSubmitted'))
            {
                $tablesSelectedArray = array();
                $tablesSelected = '';

                $num = count(self::$m_tables);
                
                // Retrieve the list of table(s) selected
                for ($i=0; $i<$num; $i++)
                {
                    if($request->postExists(self::$m_tables[$i]))
                    {
                        $tablesSelectedArray[] = self::$m_tables[$i];

                        $tablesSelected .= self::$m_tables[$i] . ';';
                    }
                }

                // Check that there is at least one table selected
                if(count($tablesSelectedArray) == 0)
                {
                    $this->app()->user()->setFlash('No table selected.');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/truncate.html');
                }

                // Truncate table(s) selected
                $managerReset = $this->m_managers->getManagerOf('reset');
                $managerReset->truncate($tablesSelectedArray);

                // Display table(s) truncated in the next flash message
                $this->app()->user()->setFlash('Table(s) truncated : "' . substr($tablesSelected, 0, strlen($tablesSelected)-1) . '".');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/truncate.html');
            }

            // Else we display the form
            $this->page()->addVar('checkboxes', self::$m_tables);
        }
    }
?>
