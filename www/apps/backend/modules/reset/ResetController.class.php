<?php
    class ResetController extends BackController
    {
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
            if($request->postExists('isSubmitted'))
            {
                $tablesSelectedArray = array();
                $tablesSelected = '';

                $num = count(self::$m_tables);
                
                for ($i=0; $i<$num; $i++)
                {
                    if($request->postExists(self::$m_tables[$i]))
                    {
                        $tablesSelectedArray[] = self::$m_tables[$i];

                        $tablesSelected .= self::$m_tables[$i] . ';';
                    }
                }

                if(count($tablesSelectedArray) == 0)
                {
                    $this->app()->user()->setFlash('No table selected.');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/truncate.html');
                }

                $managerReset = $this->m_managers->getManagerOf('reset');
                $managerReset->truncate($tablesSelectedArray);

                // Display tables truncated.
                $this->app()->user()->setFlash('Table(s) truncated : "' . substr($tablesSelected, 0, strlen($tablesSelected)-1) . '".');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/truncate.html');
            }

            else
                $this->page()->addVar('checkboxes', self::$m_tables);
        }
    }
?>
