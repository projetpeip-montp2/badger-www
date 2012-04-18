<?php
    class ClassroomManager extends Manager
    {
        public function get($id = -1)
        {
            $requestSQL = 'SELECT Id_classroom,
                                  Name,
                                  Size FROM Classrooms';

            if($id != -1)
                $requestSQL .= ' WHERE Id_classroom = ' . $id;

            $req = $this->m_dao->prepare($requestSQL);
            $req->execute(); 

            $classrooms = array();

            while($data = $req->fetch())
            {
                $classroom = new Classroom;
                $classroom->setId($data['Id_classroom']);
                $classroom->setName($data['Name']);
                $classroom->setSize($data['Size']);

                $classrooms[] = $classroom;
            }

            return $classrooms;
        }

        public function save($classrooms)
        {
            $req = $this->m_dao->prepare('INSERT INTO Classrooms(Name,
                                                                 Size) VALUES(?, ?)');

            foreach($classrooms as $classroom)
                $req->execute(array($classroom->getName(),
                                    $classroom->getSize()));
        }

        public function update($classroom)
        {
            $req = $this->m_dao->prepare('UPDATE Classrooms SET Name = ?, 
                                                                Size = ? WHERE Id_classroom = ?');

            $req->execute(array($classroom->getName(),
                                $classroom->getSize(),
                                $classroom->getId()));
        }
    }
?>
