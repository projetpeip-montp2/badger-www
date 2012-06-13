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

                // Create new element for the department
                if(!array_key_exists($userPoly->getDepartment(), $departmentsAverage))
                {
                    $departmentsAverage[$userPoly->getDepartment()] = 0;
                    $departmentsCount[$userPoly->getDepartment()] = 0;
                }

                $departmentsAverage[$userPoly->getDepartment()] += $student->getMCQMark();
                $departmentsCount[$userPoly->getDepartment()]++;
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
