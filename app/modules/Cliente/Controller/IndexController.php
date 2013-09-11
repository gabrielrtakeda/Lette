<?php
class Cliente_IndexController extends Controller
{
		public function indexAction()
		{
				$this->addLocalJs('cliente', 'cliente_common.js');
				
				if ($this->session->has('cliente_consultado'))
						$this->_childs[] = 'cliente_consulta';
				
				$this->_childs[] = 'cliente_toolbar';
				if ($this->isLogged())
						$this->render('cliente_index');
		}
}