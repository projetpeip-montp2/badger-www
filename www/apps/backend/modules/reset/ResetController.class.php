<?php
    class ResetController extends BackController
    {
        private static $m_tables = array('Answers',
                                         'AnswersOfUsers', 
                                         'Availabilities', 
                                         'BadgingInformations', 
                                         'Classrooms', 
                                         'Documents', 
                                         'Lectures', 
                                         'Questions',
                                         'QuestionsOfUsers',
                                         'Registrations',
                                         'Users');

        public function executeIndex(HTTPRequest $request)
        {
            if($request->postExists('isSubmitted'))
            {
                $tablesSelected = '';

                $num = count(self::$m_tables);
                
                for ($i=0; $i<$num; $i++)
                {
                    if($request->postExists(self::$m_tables[$i]))
                        $tablesSelected .= self::$m_tables[$i] . ';';
                }

                if($tablesSelected == '')
                {
                    $this->app()->user()->setFlash('Aucune table sélectionnée.');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/index.html');
                }

                $tablesSelected = substr($tablesSelected, 0, strlen($tablesSelected)-1);

                $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/truncate-' . $tablesSelected . '.html');
            }

            else
                $this->page()->addVar('checkboxes', self::$m_tables);
        }

        public function executeTruncate(HTTPRequest $request)
        {
            // TODO: Pour la variable $_GET, il serait interessant d'avoir une regex
            // qui force à avoir "Table1;Table2;TableN". Au moins pas besoin de se 
            // prendre la tête avec les verifications. Il faut donc pas de point 
            // virgule à la fin.

            $tablesSelected = $request->getData('tablesSelected');

            $tablesSelectedArray = explode(';', $tablesSelected);

            for ($i=0; $i<count($tablesSelectedArray); $i++)
            {
                if(!in_array($tablesSelectedArray[$i] , self::$m_tables))
                {
                    $this->app()->user()->setFlash('La table demandée n\'existe pas');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/index.html');
                }
            }

            if(!$request->postExists('isSubmitted'))
                $this->page()->addVar('tablesSelected', $tablesSelected);

            else
            {
                $manager = $this->m_managers->getManagerOf('reset');

                $manager->truncate($tablesSelectedArray);

                $this->app()->user()->setFlash('Les tables ont bien été vidée.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/index.html');
            }
        }
    }
?>
