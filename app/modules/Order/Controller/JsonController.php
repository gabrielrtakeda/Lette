<?php
class Order_JsonController extends Controller
{
		public function orderPromptAction()
		{
				$this->render('order_prompt');
		}
		
		public function consultaOrderAction()
		{
				$post		= $this->request->post['order'];
				$order	= $this->getModel('order_order');
				$this->data['orders']	= $order->consultaOrder($post['campo'], $post['valor']);
				
				$this->render('order_consulta');
		}
		
		public function setOrderAction()
		{
				$post = $this->request->post['order_data'];
				$this->session->set('prepedido_presub', $post);
				
				$sessionCliente = $this->session->get('prepedido_cliente');
				$clienteEndereco  = $sessionCliente['LocalEnt'];
				$cliente = array(
									'apelido'		=>	$sessionCliente['Apelido'],
									'razao'			=>	$sessionCliente['Razao'],
									'endereco'	=>	$clienteEndereco,
									);
				$model	= $this->getModel('prepedido_prepedido');
				$this->data['presub']	= $model->getPreSubOrder($this->session->get('prepedido_presub')['po']);
				
				$this	->setOrderNivelCreditoAction()
							->setOrderCondicaoPagamentoAction()
							->setOrderClienteEnderecoAction($clienteEndereco)
							->setOrderObservacaoAction()
							->setPreSubAction($this->data['presub'])
				;
				
				$this->data['condsPag'] = array(
																	'Antecipado',
																	'15 dias', '30 dias', '45 dias', '60 dias', '70 dias', '75 dias',
																	'60/90 dias',
																	'20/30/40 dias',
																	'30/45/60 dias',
																	'30/60/90 dias',
																	'45/60/75 dias',
																	'45/75/90 dias',
																	'45/75/105 dias',
																	'50/70/90 dias',
																	'60/70/80 dias',
																	'60/75/90 dias',
																	'30/60/90/120 dias',
																	'40/50/60/70 dias',
																	'60/70/80/90 dias',
																	'60/75/90/105 dias',
																	'30/45/60/75/90 dias',
																	'45/60/75/90/105 dias',
																	'50/60/70/80/90/100 dias',
																	'30/40/50/60/70/80/90 dias',
																	'60/65/70/75/80/85/90 dias',
																	);
				$this->data['nivCred']	= array('A', 'B', 'C', 'D');
				$this->data['theads']		= array('Cor', 'Qtde', 'Unitário', 'Total', 'Saldo', 'Estoque');
				$this->data['prepedido']	= array(
																		'cliente' => $cliente,
																		'order' => $this->session->get('prepedido_presub'),
																		);
				
				$this->render('prepedido_cadastro');
		}
		
		public function setOrderNivelCreditoAction($nc = 'A')
		{
				if (isset($this->request->post['nivel_credito']))
						$nc = $this->request->post['nivel_credito'];
				$this->session->set('prepedido_nivel_credito', $nc);
				return $this;
		}
		
		public function setOrderCondicaoPagamentoAction($cp = 'Antecipado')
		{
				if (isset($this->request->post['condicao_pagamento']))
						$cp = $this->request->post['condicao_pagamento'];
				$this->session->set('prepedido_condicao_pagamento', $cp);
				return $this;
		}
		
		public function setOrderClienteEnderecoAction($ce)
		{
				if (isset($this->request->post['cliente_endereco']))
						$ce = $this->request->post['cliente_endereco'];
				$this->session->set('prepedido_cliente_endereco', $ce);
				return $this;
		}
		
		public function setOrderObservacaoAction($ps = '')
		{
				if (isset($this->request->post['observacao']))
						$ps = $this->request->post['observacao'];
				$this->session->set('prepedido_observacao', $ps);
				return $this;
		}
		
		public function setPreSubAction($ps)
		{
				$presub_items = array();
				foreach ($ps->FETCH_OBJECT as $ps) {
						$presub_items[] = array(// 'Material'	=> $this->session->get('prepedido_presub')['material'],
																		// 'PO'				=> $this->session->get('prepedido_presub')['po'],
																		'Cor'				=> $ps->Cor,
																		'Preco'			=> 0.00,
																		'Qtde'			=> 0.00,
																		);
				}
				$this->session->set('prepedido_presub_items', $presub_items);
				return $this;
		}
		
		public function setPreSubQuantidadeAction()
		{
				$presub = $this->request->post['presub'];
				echo $this->session->setChilds('prepedido_presub_items->'. $presub['index'] .'->Qtde', (float) $presub['value']);
		}
		
		public function setPreSubPrecoAction()
		{
				$presub = $this->request->post['presub'];
				echo $this->session->setChilds('prepedido_presub_items->'. $presub['index'] .'->Preco', (float) $presub['value']);
		}
}









