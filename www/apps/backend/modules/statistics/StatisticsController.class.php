<?php
    class StatisticsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Statistiques");

            $studentsManager = $this->m_managers->getManagerOf('user');
            $students = $studentsManager->get();

            $studentsPerDepartments = array();

            $average = 0;

            $departmentsAverage = array();
            $departmentsCount = array();
            foreach($students as $student)
            {
                $average += $student->getMCQMark();

                $userPoly = $studentsManager->retrieveStudentFromPolytech($student->getUsername());

                $departmentAndSchoolyear = $userPoly->getDepartment() . $userPoly->getSchoolyear();
                $mcqExists = count($this->m_managers->getManagerOf('mcq')->get($userPoly->getDepartment(), $userPoly->getSchoolyear())) != 0 ? true : false;

                // Create new element for the department
                // Check that the department is in the array && has a MCQ in the database
                if(!array_key_exists($departmentAndSchoolyear, $departmentsAverage) && $mcqExists)
                {
                    $departmentsAverage[$departmentAndSchoolyear] = 0;
                    $departmentsCount[$departmentAndSchoolyear] = 0;
                }

                if($mcqExists)
                {
                    $departmentsAverage[$departmentAndSchoolyear] += $student->getMCQMark();
                    $departmentsCount[$departmentAndSchoolyear]++;
                }
            }

            foreach($departmentsAverage as $name => &$department)
            {
                $department /= $departmentsCount[$name];
            }

            $this->page()->addVar('average', $average / count($students));
            $this->page()->addVar('departmentsAverage', $departmentsAverage);
        }
    }
?>
