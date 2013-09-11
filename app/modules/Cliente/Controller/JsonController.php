<?php
class Cliente_JsonController extends Controller
{
		public function consultaClienteAction()
		{
				$repcod	 = (isset($this->session->get('login')['cod']) ? $this->session->get('login')['cod'] : -1);
				$apelido = $this->request->post['apelido'];
				$cliente = $this->getModel('cliente_cliente');
				$this->data['clientes'] = $cliente->consultaCliente($repcod, $apelido);
				
				$this->render('cliente_prompt');
		}
		
		public function setClienteAction()
		{
				$params 	= $this->getParams(array('Cliente'));
				$model		= $this->getModel('cliente_cliente');
				$cliente	= $model->getCliente('Apelido', $params['Cliente'], array('Apelido', 'Razao', 'LocalEnt', 'BairroEnt', 'CidadeEnt', 'EsEnt', 'CepEnt'));
				
				$this->session->set('prepedido_cliente', $cliente->FETCH_ARRAY[0]);
		}
		
		public function sessionClienteAction()
		{
				echo $this->session->get('prepedido')['cliente'];
		}
		
		public function getClienteAction()
		{
				$params		= $this->getParams(array('Apelido'));
				$model		= $this->getModel('cliente_cliente');
				$cliente	= $model->getCliente('Apelido', $params['Apelido'], array('Apelido', 'Razao', 'Endereco', 'Bairro', 'Cidade', 'Es', 'Cep', 'CGC', 'Inscricao', 'DDD', 'Tel', 'Fax', 'Rep', 'Transporte'));
				
				$this->session->set('cliente_consultado', $cliente->FETCH_OBJECT[0]);
				
				$this->render('cliente_consulta');
		}
}