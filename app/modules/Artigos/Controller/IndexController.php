<?php
class Artigos_IndexController extends Controller
{
		public function indexAction()
		{
				$this->addLocalJs('artigos', 'artigos_common.js');
		
				$model	= $this->getModel('artigos_artigos');
				$this->data['page']			= ($this->session->has('artigos') ? $this->session->get('artigos')['page'] : 1);
				$this->data['limit']		= 25;
				$this->data['artigos']	= $model->getArtigos();
				$this->data['num_row']	= $model->getNumRow()->FETCH_OBJECT[0]->NumRow;
		
				$this->_childs[] = 'artigos_toolbar';
				if ($this->isLogged())
						$this->render('artigos_index');
		}
}