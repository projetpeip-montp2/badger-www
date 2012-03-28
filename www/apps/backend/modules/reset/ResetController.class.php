<?php
    class ResetController extends BackController
    {
        private static $m_tables = array('Answers' => '',
                                         'AnswersOfUsers' => '', 
                                         'Availabilities' => '', 
                                         'BadgingInformations' => '', 
                                         'Classrooms' => '', 
                                         'Documents' => '', 
                                         'Lectures' => '', 
                                         'Questions' => '', 
                                         'Registrations' => '',
                                         'Users' => '');

        public function executeIndex(HTTPRequest $request)
        {
            $select = array();

            foreach(self::$m_tables as $key => $value)
                $select[$key] = $key;

            if ($request->postExists('vbmifareTable'))
            {
                $tableSelected = $request->postData('vbmifareTable');

                if(!in_array($tableSelected, $select))
                {
                    $this->app()->user()->setFlash('La table demandée n\'existe pas');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/index.html');
                }

                $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/truncate-' . $tableSelected . '.html');
            }

            else
                $this->page()->addVar('select', $select);
        }

        public function executeTruncate(HTTPRequest $request)
        {
            if(!$request->postExists('isSubmitted'))
            {
                $tableSelected = $request->getData('tableSelected');

                $select = array();

                foreach(self::$m_tables as $key => $value)
                    $select[$key] = $key;

                if(!in_array($tableSelected, $select))
                {
                    $this->app()->user()->setFlash('La table demandée n\'existe pas');
                    $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/index.html');
                }

                $this->page()->addVar('tableSelected', $tableSelected);
            }

            else
            {
                $this->app()->user()->setFlash('Suppresion de table en cours dìmplémentation');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/reset/index.html');
            }
        }
    }
?>
