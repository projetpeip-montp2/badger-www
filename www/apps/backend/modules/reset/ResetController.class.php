<?php
    class ResetController extends BackController
    {
        // List of all tables in Database
        private static $m_tables = array('Answers',
                                         'AnswersOfUsers', 
                                         'ArchivesOfPackages',
                                         'Availabilities', 
                                         'BadgingInformations', 
                                         'Classrooms', 
                                         'DocumentsOfPackages', 
                                         'DocumentsOfUsers',
                                         'HistoryMifare', 
                                         'ImagesOfArchives', 
                                         'MCQs', 
                                         'Lectures', 
                                         'Packages', 
                                         'Questions',
                                         'QuestionsOfUsers',
                                         'Registrations',
                                         'ReplicationLogs',
                                         'Users',
                                         'UsersPolytech');

        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Remise à zéro");
        }

        public function executeTruncate(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Vider les tables");

            // If the form is submitted
            if($request->postExists('Vider'))
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
                    $this->app()->user()->setFlashError('Aucune table sélectionnée.');
                    $this->app()->httpResponse()->redirect('/admin/reset/truncate.html');
                }

                // Truncate table(s) selected
                $managerReset = $this->m_managers->getManagerOf('reset');
                $managerReset->truncate($tablesSelectedArray);

                // Display table(s) truncated in the next flash message
                $emptyTables = explode(';', substr($tablesSelected, 0, strlen($tablesSelected)-1));

                $flashMessage = 'Table(s) vidée(s) :';
                foreach($emptyTables as $emptyTable)
                    $flashMessage .= '<br/>' . $emptyTable;

                $this->app()->user()->setFlashInfo($flashMessage);
                $this->app()->httpResponse()->redirect('/admin/reset/truncate.html');
            }

            // Else we display the form
            $this->page()->addVar('checkboxes', self::$m_tables);
        }

        public function executeDelete(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Suppression des fichiers physiques");

            // List of directories containing admin's and student's files
            $directories = array('admin/pdf' => dirname(__FILE__).'/../../../../uploads/admin/pdf/',
                                  'admin/images' => dirname(__FILE__).'/../../../../uploads/admin/images/',
                                  'students' => dirname(__FILE__).'/../../../../uploads/students/');

            // If the form is submitted
            if($request->postExists('Vider'))
            {
                $directoriesSelectedArray = array();
                $directoriesSelected = '';

                // Retrieve the list of directory(ies) selected
                foreach(array_keys($directories) as $directory)
                {
                    if($request->postExists($directory))
                    {
                        $directoriesSelectedArray[] = $directory;

                        $directoriesSelected .= $directory . ';';
                    }
                }

                // Check that there is at least one directory selected
                if(count($directoriesSelectedArray) == 0)
                {
                    $this->app()->user()->setFlashError('Aucun répertoire sélectionné.');
                    $this->app()->httpResponse()->redirect('/admin/reset/delete.html');
                }

                // Delete files from directories selected
                foreach($directoriesSelectedArray as $dirName)
                    $this->deletePhysicalFiles($directories[$dirName]);

                // Display directories emptied in the next flash message
                $emptyDirectories = explode(';', substr($directoriesSelected, 0, strlen($directoriesSelected)-1));

                $flashMessage = 'Répertoire(s) vidé(s) :';
                foreach($emptyDirectories as $emptyDirectory)
                    $flashMessage .= '<br/>' . $emptyDirectory;

                $this->app()->user()->setFlashInfo($flashMessage);
                $this->app()->httpResponse()->redirect('/admin/reset/delete.html');
            }

            // Else we display the form
            $this->page()->addVar('checkboxes', array_keys($directories));
        }

        private function deletePhysicalFiles($dirName)
        {
            // Files not to be deleted
            $specificFiles = array('.', '..', 'README');

            // Open directory
            if ($directory = opendir($dirName))
            {
                while (false !== ($file = readdir($directory)))
                {
                    if (!in_array($file, $specificFiles))
                        unlink($dirName . $file);
                }
                closedir($directory);
            }
        }
    }
?>
