<?php
    class RetrievalController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('viewTitle', 'Récupération de données');
        }

                public function executeGetMarks(HTTPRequest $request)
        {
            // Hack to don't display the layout :)
			$this->page()->setIsAjaxPage(TRUE);

            $csv = '// "Department","SchoolYear","Username","Mark","Comment" ' . PHP_EOL;

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
                                  $student->getMark() . '"';

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
            $lectureCSV = '';
            $questionCSV = '';

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

                $zip->addfile($lectureCSV, 'packages/' . $package->getName('fr') . '/lectures.csv');
                $zip->addfile($questionCSV, 'packages/' . $package->getName('fr') . '/questions-answers.csv');
            }

            $classrooms = $this->m_managers->getManagerOf('classroom')->get();

            $availabilitesManager = $this->m_managers->getManagerOf('availability');

            $classroomCSV = '';
            $availabilityCSV = '';

            // Generate classrooms.csv
            foreach($classrooms as $classroom)
            {
                $classroomCSV .= '"' . $classroom->getName() . '","' . 
                                       $classroom->getSize() . '"';
                $classroomCSV .= PHP_EOL;

                // Generate availabilities.csv
                $availabilities = $availabilitesManager->get($classroom->getId());
                foreach($availabilities as $availability)
                {
                    $availabilityCSV .= '"' . $availability->getDate() . '","' . 
                                              $availability->getStartTime() . '","' .
                                              $availability->getEndTime() . '"';
                    $availabilityCSV .= PHP_EOL;
                }

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
