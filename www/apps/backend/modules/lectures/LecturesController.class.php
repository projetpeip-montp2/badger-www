<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeAddLectures(HTTPRequest $request)
        {
            if ($request->fileExists('vbmifareFileCSV'))
            {
                $fileData = $request->fileData('vbmifareFileCSV');

                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $lectures = array();

                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 9)
                        {
                            $this->app()->user()->setFlash('File has not got 9 rows');
                            $this->app()->httpResponse()->redirect('./addLectures.html');
                            break;
                        }
        
                        $lecture = new Lecture;
                        $lecture->setNameFr($lineDatas[0]);
                        $lecture->setNameEn($lineDatas[1]);
                        $lecture->setLecturer($lineDatas[2]);
                        $lecture->setDescriptionFr($lineDatas[3]);
                        $lecture->setDescriptionEn($lineDatas[4]);
                        $lecture->setDate($lineDatas[5]);
                        $lecture->setStartTime($lineDatas[6]);
                        $lecture->setEndTime($lineDatas[7]);
                        $lecture->setTags($lineDatas[8]);

                        array_push($lectures, $lecture);
                    }

                    fclose($file);

                    $manager = $this->m_managers->getManagerOf('lecture');
                    $manager->save($lectures);

                    $this->app()->user()->setFlash('File uploaded');
                }

                else
                    $this->app()->user()->setFlash('Error during the upload of lectures');
            }
        }
    }
?>
