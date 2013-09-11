<?php
class Prepedido_PrepedidoModel extends Model
{
		public function getPrepedidos($repcod, $rep)
		{
				return $this->db->setTable('dbo.PrePedido')
												->setFields(array('PedidoN', 'Cliente', 'Data', 'ST', 'Pagamento', 'NFN', 'PO', 'Joo', 'Suk', 'Material', 'Obs'))
												->setWhere("RepCod = '{$this->db->escape($repcod)}' AND Rep = '{$this->db->escape($rep)}'")
												->setOrder('PedidoN DESC')
												->setLimit(50)
												->select();
		}
		
		public function getPrepedido($pedidoN)
		{
				return $this->db->setTable('dbo.PreSub')
												->setWhere("PedidoN = '{$this->db->escape($pedidoN)}'")
												->select();
		}
		
		public function getPreSub($pedidoN)
		{
				return $this->db->setTable('dbo.PreSub')
												->setWhere("PedidoN='{$this->db->escape($pedidoN)}'")
												->setFields(array('*, (Preco * Qtde) as Total'))
												->select();
		}
		
		public function getPrepedidoByN($pedidoN)
		{
				return $this->db->setTable('dbo.PrePedido')
												->setFields(array('PedidoN', 'Cliente', 'Data', 'ST', 'Pagamento', 'NFN', 'PO', 'Joo', 'Suk', 'Material', 'Obs'))
												->setWhere("PedidoN Like '%{$this->db->escape($pedidoN)}%'")
												->setOrder('PedidoN DESC')
												->setLimit(50)
												->select();
		}
		
		public function getPreOrder($po)
		{
				return $this->db->setTable('dbo.PreOrder')
												->setWhere("PO = {$this->db->escape($po)}")
												->select();
		}
		
		public function getPreSubOrder($po)
		{
				return $this->db->setTable('dbo.PreSubOrder')
												->setFields(array('*', '(Qtde - Pedido) as Saldo'))
												->setWhere("PO = '{$this->db->escape($po)}'")
												->select();
		}
		
		public function cadastraPrepedido($prepedido) // PrePedido: p
		{
				$config = $this->db->setTable('dbo.Config')->setFields(array('Title', 'PreLetra', 'PrePedido'))->select()->FETCH_OBJECT[0];
				$nextID = $config->PreLetra . $this->numbers->zeroMask($config->PrePedido, 4);
				
				$l = $config->PreLetra;
				$p = ($config->PrePedido + 1);
				if ($p == '9999') {
						$p = 1;
						if ((ord($l) < 90) && (ord($l) >= 65))
								$l = chr(ord($l) + 1);
						else
								$l = 'A';
				}
				$sql  = "UPDATE dbo.Config SET PreLetra = '{$l}', PrePedido = '{$p}' WHERE Title = '{$config->Title}'";
								$this->db->save($sql);
				
				$sql  = 'INSERT INTO dbo.PrePedido (PedidoN, Cliente, ST, LocalEnt, Data, Pagamento, Rep, RepCod, Obs, Material, Rej, PO) VALUES ';
				$sql .= "('{$nextID}', 
									'{$this->db->escape($prepedido['Cliente'])}', 
									'{$this->db->escape($prepedido['ST'])}', 
									'{$this->db->escape($prepedido['LocalEnt'])}', 
									'". date('Y-m-d') ."', 
									'{$this->db->escape($prepedido['Pagamento'])}', 
									'{$this->db->escape($prepedido['Rep'])}', 
									'{$this->db->escape($prepedido['RepCod'])}', 
									'". utf8_decode($this->db->escape($prepedido['Obs'])) ."', 
									'{$this->db->escape($prepedido['Material'])}', 
									0, 
									'{$this->db->escape($prepedido['PO'])}')";
									$this->db->save($sql);
				
				foreach ($prepedido['PreSub'] as $presub) {
						$sql  = 'INSERT INTO dbo.PreSub (PedidoN, PO, Material, Cor, Preco, Qtde, Original) VALUES ';
						$sql .= "('{$nextID}', 
											'{$prepedido['PO']}', 
											'{$prepedido['Material']}', 
											'{$presub['Cor']}', 
											{$presub['Preco']}, 
											{$presub['Qtde']}, 
											'0');";
											$this->db->save($sql);
				}
		}
}












