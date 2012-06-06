<?php
    class StatisticsController extends BackController
    {
        public function executeIndex(HTTPRequest $request)
        {
            $this->page()->addVar("viewTitle", "Statistiques");
        }
    }
?>
