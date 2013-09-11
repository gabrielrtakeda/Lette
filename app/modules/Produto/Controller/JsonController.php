<?php
class Produto_JsonController extends Controller
{
		private $json = array();
		
		public function getProdutoAction()
		{
				$produtos = $this->db	->setTable('dbo.Produto')
															->setWhere("Codigo LIKE '%{$this->db->escape($this->request->post['valor'])}%'")
															->setOrder("Codigo ASC")
															->setFields(array('Codigo', 'CF', 'CFCodigo', 'Origem', 'LE', 'Comp1', 'Comp2', 'Comp3', 'Comp4', 'Unidade', 'Peso', 'Larg', 'Custo', 'DI', 'Processo'))
															->select();
				$this->session->set('consulta_produto', $produtos);
				$this->data['produtos'] = $produtos;
				
				$this->render('produto_prompt');
		}
		
		public function getCodigoProdutoAction()
		{
				echo $this->session->get('produto_consultado')['id'];
		}
		
		public function getHtmlProdutoAction()
		{
				echo $this->session->get('produto_consultado')['html'];
		}
		
		public function getCorAction()
		{
				if (isset($this->request->post['valor']))
							$valor = $this->request->post['valor'];
				else	$valor = '';
				
				if ($this->session->has('consulta_produto'))
						$session = $this->session->get('consulta_produto');
				
				$this->data['produto'] = array();
				foreach ($session->FETCH_OBJECT as $produto) {
						if ($produto->Codigo == $valor) {
								$this->data['produto'] = $produto;
						break;
						}
				}
						
				$this->session->delete('consulta_produto');
				$this->session->set('produto_consultado', $this->data['produto']);
				$this->render('produto_consulta');
		}
		
		public function reloadProdutoAction()
		{
				utf8_decode($this->render('produto_consulta'));
		}
}