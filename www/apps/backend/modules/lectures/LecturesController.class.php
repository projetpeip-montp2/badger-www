<?php
    class LecturesController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {

        }

        public function executeAddPackages(HTTPRequest $request)
        {
            if ($request->fileExists('vbmifarePackagesCSV'))
            {
                $fileData = $request->fileData('vbmifarePackagesCSV');

                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $packages = array();

                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 2)
                        {
                            $this->app()->user()->setFlash('File has not got 2 rows');
                            $this->app()->httpResponse()->redirect('./addPackages.html');
                            break;
                        }
        
                        $package = new Package;
                        $package->setNameFr($lineDatas[0]);
                        $package->setNameEn($lineDatas[1]);

                        array_push($packages, $package);
                    }

                    fclose($file);

                    $manager = $this->m_managers->getManagerOf('package');
                    $manager->save($packages);

                    $this->app()->user()->setFlash('File uploaded');
                }

                else
                    $this->app()->user()->setFlash('Error during the upload of packages');
            }
        }


        public function executeAddLecturesAndQuestionsAnswers(HTTPRequest $request)
        {
            if($request->fileExists('vbmifareLecturesCSV'))
            {
                $fileData = $request->fileData('vbmifareLecturesCSV');

                if($fileData['error'] == 0)
                {
                    $file = fopen($fileData['tmp_name'], 'r');

                    $lectures = array();

                    while (($lineDatas = fgetcsv($file)) !== FALSE) 
                    {
                        if(count($lineDatas) != 8)
                        {
                            $this->app()->user()->setFlash('Lecture csv file has not got 8 rows.');
                            $this->app()->httpResponse()->redirect('./addLecturesAndQuestionsAnswers.html');
                        }
        
                        $lecture = new Lecture;
                        $lecture->setIdPackage($request->postData('vbmifarePackage'));
                        $lecture->setNameFr($lineDatas[0]);
                        $lecture->setNameEn($lineDatas[1]);
                        $lecture->setLecturer($lineDatas[2]);
                        $lecture->setDescriptionFr($lineDatas[3]);
                        $lecture->setDescriptionEn($lineDatas[4]);
                        $lecture->setDate($lineDatas[5]);
                        $lecture->setStartTime($lineDatas[6]);
                        $lecture->setEndTime($lineDatas[7]);

                        array_push($lectures, $lecture);
                    }

                    fclose($file);

                    $manager = $this->m_managers->getManagerOf('lecture');
                    $manager->save($lectures);

                    $this->app()->user()->setFlash('Lectures uploaded.');
                }

                else
                    $this->app()->user()->setFlash('Cannot upload lectures.');
            }

            if($request->fileExists('vbmifareQuestionsAnswersCSV'))
            {
                $this->app()->user()->setFlash('Questions/Answers upload not implemented');
                $this->app()->httpResponse()->redirect('./addLecturesAndQuestionsAnswers.html');
            }

            $lang = $this->app()->user()->getAttribute('vbmifareLang');

            $managerPackages = $this->m_managers->getManagerOf('package');
            $packages = $managerPackages->get($lang);

            if( count($packages) == 0)
            {
                $this->app()->user()->setFlash('You need at least a package to upload Lectures or Questions/Answers.');
                $this->app()->httpResponse()->redirect('/vbMifare/admin/home/index.html');
            }

            $this->page()->addVar('packages', $packages);
        }
    }
?>
