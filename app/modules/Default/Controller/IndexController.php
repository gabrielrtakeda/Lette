<?php
class Default_IndexController extends Controller
{
		public function indexAction()
		{
				$this->addLocalJs('default', 'common.js');
				
				$this->_childs['toolbar'] = 'prepedido_toolbar';
				$this->_childs['prepedido'] = 'prepedido_index';
				
				if ($this->isLogged()) $this->url->redirect('prepedido');
						// $this->render('default_home');
		}
}