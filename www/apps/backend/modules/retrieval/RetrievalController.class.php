<?php
    class RetrievalController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', 'Récupération de données');
        }

        public function executeGetReports(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', 'Récupération des rapports');

            if($request->postExists('Récupérer'))
            {
                $idLecture = $request->postData('idLecture');

                echo $idLecture;

                $lecture = $this->m_managers->getManagerOf('lecture')->get(-1, $idLecture);

                $lectureName = $lecture[0]->getName('fr');

                $reports = $this->m_managers->getManagerOf('documentofuser')->get($idLecture);

                $zip = new zipfile();

                foreach($reports as $report)
                {
                    $filename = dirname(__FILE__).'/../../../../uploads/students/' . $report->getFilename();

                    $fo = fopen($filename, 'r');
                    if(!$fo)
                    {
                        // TODO: Trouver une solution
                    }

                    $contenu = fread($fo, filesize($filename));
                    fclose($fo);

                    $zip->addfile($contenu, $report->getFilename());
                }


                header('Content-Type: application/x-zip');
                header('Content-Disposition: inline; filename=' . $lectureName . '.zip');

                echo $zip->file();
            }

            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get();

            $managerLectures = $this->m_managers->getManagerOf('lecture');
            $lectures = $managerLectures->get();

            $this->page()->addVar('packages', $packages);
            $this->page()->addVar('lectures', $lectures);
        }

        public function executeGetMarks(HTTPRequest $request)
        {
            // Hack to don't display the layout :)
			$this->page()->setIsAjaxPage(TRUE);

            $csv = '"Département","Année d\'étude","Username","Note de présence","Note QCM","Commentaire"' . PHP_EOL;

            $managerUser = $this->m_managers->getManagerOf('user');
            $students = $managerUser->get();

            foreach($students as $student)
            {
                $status = $student->getMCQStatus();

                if($status != 'Visitor')
                {
                    $shoolYear = intval($student->getSchoolYear()) + 2;

                    $csv .= '"' . $student->getDepartment() . '","' . 
                                  $shoolYear . '","' . 
                                  $student->getStudentNumber() . '","' . 
                                  $student->getUsername() . '","' . 
                                  $student->getPresentMark() . '","' . 
                                  $student->getMCQMark() . '"';

                    $csv .= (($status == 'Taken') ? ',""' : ',"Absent"');

                    $csv .= PHP_EOL;
                }
            }

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="marks.csv"');
            echo $csv;
        }

        public function executeExport(HTTPRequest $request)
        {
            // Hack to don't display the layout :)
			$this->page()->setIsAjaxPage(TRUE);

            $zip = new zipfile();

            $packages = $this->m_managers->getManagerOf('package')->get();

            $lecturesManager = $this->m_managers->getManagerOf('lecture');
            $questionsManager = $this->m_managers->getManagerOf('question');
            $answersManager = $this->m_managers->getManagerOf('answer');

            $packageCSV = '';

            // Generate packages.csv
            foreach($packages as $package)
            {
                $packageCSV .= '"' . $package->getCapacity() . '","' . 
                              $package->getName('fr') . '","' . 
                              $package->getName('en') . '","' . 
                              $package->getDescription('fr') . '","' . 
                              $package->getDescription('en') . '"';
                $packageCSV .= PHP_EOL;

                // Generate lectures.csv
                $lectureCSV = '';
                $lectures = $lecturesManager->get($package->getId());
                foreach($lectures as $lecture)
                {
                    $lectureCSV .= '"' . $lecture->getLecturer() . '","' . 
                                  $lecture->getName('fr') . '","' . 
                                  $lecture->getName('en') . '","' . 
                                  $lecture->getDescription('fr') . '","' . 
                                  $lecture->getDescription('en') . '","' . 
                                  $lecture->getDate() . '","' . 
                                  $lecture->getStartTime() . '","' .
                                  $lecture->getEndTime() . '"';
                    $lectureCSV .= PHP_EOL;
                }

                // Generate questions-answers.csv
                $questionCSV = '';
                $questions = $questionsManager->get($package->getId());
                foreach($questions as $question)
                {
                    $questionCSV .= '"' . $question->getLabel('fr') . '","' . 
                                          $question->getLabel('en') . '","' . 
                                          $question->getStatus() . '"';
                    $questionCSV .= PHP_EOL;

                    $answers = $answersManager->get($question->getId());
                    foreach($answers as $answer)
                    {
                        $questionCSV .= '"' . $answer->getLabel('fr') . '","' . 
                                              $answer->getLabel('en') . '","' . 
                                              $answer->getTrueOrFalse() . '"';
                        $questionCSV .= PHP_EOL;
                    }

                    // Add question separator
                    $questionCSV .= '__vbmifare*';
                    $questionCSV .= PHP_EOL;
                }

                // Check if there is at least one element to save
                if($lectureCSV != '')
                    $zip->addfile($lectureCSV, 'packages/' . $package->getName('fr') . '/lectures.csv');
                if($questionCSV != '')
                $zip->addfile($questionCSV, 'packages/' . $package->getName('fr') . '/questions-answers.csv');
            }

            $classrooms = $this->m_managers->getManagerOf('classroom')->get();

            $availabilitesManager = $this->m_managers->getManagerOf('availability');

            $classroomCSV = '';

            // Generate classrooms.csv
            foreach($classrooms as $classroom)
            {
                $classroomCSV .= '"' . $classroom->getName() . '","' . 
                                       $classroom->getSize() . '"';
                $classroomCSV .= PHP_EOL;

                // Generate availabilities.csv
                $availabilityCSV = '';
                $availabilities = $availabilitesManager->get($classroom->getId());
                foreach($availabilities as $availability)
                {
                    $availabilityCSV .= '"' . $availability->getDate() . '","' . 
                                              $availability->getStartTime() . '","' .
                                              $availability->getEndTime() . '"';
                    $availabilityCSV .= PHP_EOL;
                }

                // Check if there is at least one element to save
                if($availabilityCSV != '')
                    $zip->addfile($availabilityCSV, 'classrooms/' . $classroom->getName() . '/availabilities.csv');
            }

            $zip->addfile($packageCSV, 'packages.csv');
            $zip->addfile($classroomCSV, 'classrooms.csv');

            header('Content-Type: application/x-zip');
            header('Content-Disposition: inline; filename=exportCSV.zip');

            echo $zip->file();
        }        
    }
?>
