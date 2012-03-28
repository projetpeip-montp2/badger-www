<?php
    class McqController extends BackController
    {
        ////////////////////////////////////////////////////////////
        /// \brief Execute action Index
        ////////////////////////////////////////////////////////////
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar('showMCQLink', $this->canTakeMCQ());
        }

        ////////////////////////////////////////////////////////////
        /// \brief Execute action TakeMCQ
        ////////////////////////////////////////////////////////////
        public function executeTakeMCQ(HTTPRequest $request)
        {
            if(!$this->canTakeMCQ())
            {
                // Inclusion of the langage file
                require_once(dirname(__FILE__).'/../../lang/'.$this->app()->user()->getAttribute('vbmifareLang').'.php');
                $this->app()->user()->setFlash($TEXT['NoTakeMCQ']);
                $this->page()->addVar('showMCQLink', false);
                $this->app()->httpResponse()->redirect('/vbMifare/mcq/index.html');
            }

            
        }

        ////////////////////////////////////////////////////////////
        /// \brief Can Take the MCQ if correct date or not taken already
        ///
        /// \return Boolean
        ////////////////////////////////////////////////////////////
        private function canTakeMCQ()
        {
            $hasTakenMCQ = $this->app()->user()->getAttribute('vbmifareStudent')->hasTakenMCQ();

            $startDate = new Date;
            $currentDate = new Date;
            $startDate->setFromString($this->app()->configGlobal()->get('MCQStartDate'));
            $currentDate->setFromString(date('d-m-y'));

            return !($hasTakenMCQ || Date::compare($startDate, $currentDate) == 1);
        }
    }
?>
