<?php
class Prepedido_IndexController extends Controller
{
		public function indexAction()
		{
				$this->addLocalJs('prepedido', 'prepedido_common.js');
				
				$prepedido = $this->getModel('prepedido_prepedido');
				$login = $this->session->get('login');
				$this->data['prepedidos'] = $prepedido->getPrepedidos($login['cod'], $login['apelido']);
				
				$this->_childs[] = 'prepedido_toolbar';
				
				if ($this->isLogged())
						$this->render('prepedido_index');
		}
}