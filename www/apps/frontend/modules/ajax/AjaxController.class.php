<?php
    require_once dirname(__FILE__).'/../../BackControllerFrontend.class.php';

    class AjaxController extends BackControllerFrontend
    {
    	private $m_ajaxContent = '';

        public function executeIndex(HTTPRequest $request)
        {
			$this->app()->httpResponse()->redirect('/home/index.html');
        }
		
		public function executeModifyText(HTTPRequest $request)
		{
			
		}
		public function executeDeleteEntry(HTTPRequest $request)
		{
			$this->page()->setIsAjaxPage(TRUE);
			
			$allowedEntries = array('DocumentsOfUsers');

			$idFields = array('DocumentsOfUsers' => 'Id_document');
			
			if($request->postExists('data-entry-name') && $request->postExists('data-id'))
			{
				$ajaxInput = new AjaxInput;
				$ajaxInput->setData('entry-name', $request->postData('data-entry-name'));
				$ajaxInput->setData('id', $request->postData('data-id'));

				if (!in_array($ajaxInput->getData('entry-name'), $allowedEntries))
                    $this->addToAjaxContent('Erreur dans le formulaire.');

				else
				{
					$ajaxInput->setData('id-name', $idFields[$request->postData('data-entry-name')]);
					
					try
					{
						$this->m_managers->getManagerOf('ajax')->deleteEntry($ajaxInput);
					}
					catch (Exception $e)
					{
						$this->addToAjaxContent('Erreur de suppression');
					}
				}
			}
			echo $this->getAjaxContent();
		}
		
		public function getAjaxContent()
		{
			return ($this->m_ajaxContent);
		}
		
		public function setAjaxContent($value)
		{
			$this->m_ajaxContent = $value;
		}
		
		public function addToAjaxContent($value)
		{
			$this->m_ajaxContent .= $value;
		}
	}
?>
