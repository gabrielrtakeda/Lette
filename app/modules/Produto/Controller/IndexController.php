<?php
class Produto_IndexController extends Controller
{
		public function indexAction()
		{
				$this->addLocalJs('produto', 'common.js');
				$produto = $this->getModel('produto_index');
				$this->data['produtos'] = $this->db	->setTable('dbo.Produto')
																						->setOrder('Codigo ASC')
																						->setLimit(15)
																						->select()
				;
				// $this->data['produtos'] = $produto->getProdutos();
				$this->data['prodCor'] = $produto->getCores(1, '5910-TLC-CARDIGA');
				
				$this->_childs[] = 'produto_toolbar';
				
				if ($this->session->has('produto_consultado'))
						$this->_childs[] = 'produto_consulta';
				
				if ($this->isLogged())
						$this->render('produto_index');
		}
}