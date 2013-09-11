<?php
class Prepedido_JsonController extends Controller
{
		public function getPrepedidoAction()
		{
				$pedidoN = $this->request->post['pedidoN'];
				$prepedido = $this->getModel('prepedido_prepedido');
				
		}
		
		public function newPrepedidoAction()
		{
				$this->render('prepedido_incluir');
		}
		
		public function consultaClienteAction()
		{
				$this->render('prepedido_consultaCliente');
		}
		
		public function getPreSubAction()
		{
				$params			= $this->getParams(array('PedidoN'));
				$prepedido	= $this->getModel('prepedido_prepedido');
				
				$this->data['presub'] = $prepedido->getPreSub($params['PedidoN']);
				
				$this->render('prepedido_presub');
		}
		
		public function getPrepedidoByNAction()
		{
				$params = $this->getParams(array('PedidoN'));
				$prepedido = $this->getModel('prepedido_prepedido');
				$this->data['prepedidos'] = $prepedido->getPrepedidoByN($params['PedidoN']);
				
				$this->render('prepedido_jsonTable');
		}
		
		public function getPrepedidoCadastradoAction()
		{
				$prepedido = array(	'Cliente'		=> $this->session->get('prepedido_cliente')['Apelido'],
														'ST'				=> $this->session->get('prepedido_nivel_credito'),
														'LocalEnt'	=> $this->session->get('prepedido_cliente_endereco'),
														'Data'			=> date('Y-m-d'),
														'Pagamento'	=> $this->session->get('prepedido_condicao_pagamento'),
														'Rep'				=> $this->session->get('login')['apelido'],
														'RepCod'		=> $this->session->get('login')['cod'],
														'Obs'				=> $this->session->get('prepedido_observacao'),
														'Material'	=> $this->session->get('prepedido_presub')['material'],
														'PO'				=> $this->session->get('prepedido_presub')['po'],
														'PreSub'		=> $this->session->get('prepedido_presub_items'),
													);
				
				return $prepedido;
		}
		
		public function cancelaPrepedidoAction()
		{
				$keys = array_keys($this->session->get());
				$count = 0;
				foreach ($keys as $key) {
						if (preg_match('/^prepedido_/i', $key)) $this->session->delete($key);
				}
		}
		
		public function cadastraPrepedidoAction()
		{
				$model			= $this->getModel('prepedido_prepedido');
				$prepedido	= $model->cadastraPrepedido($this->getPrepedidoCadastradoAction());
				var_dump($prepedido);
		}
}