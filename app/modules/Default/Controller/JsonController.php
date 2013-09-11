<?php
class Default_JsonController extends Controller
{
		public function setFirmaAction()
		{
				$params	= $this->getParams(array('idFirma'));
				$model	= $this->getModel('default_firma');
				$firma	= $model->getFirma($params['idFirma']);
				$firmaDefault	= array(
						'id'				=> $firma->IDFirma,
						'cnpj'			=> $firma->CNPJ,
						'fantasia'	=> $firma->Fantasia,
						'razao'			=> $firma->Razao,
				);
				
				$this->session->set('firma', $firmaDefault);
		}
}