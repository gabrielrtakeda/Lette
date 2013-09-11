<?php
class Cliente_ClienteModel extends Model
{
		public function consultaCliente($repcod, $apelido)
		{
				return $this->db->setTable('dbo.Cliente')
												->setFields(array('Codigo', 'Apelido', 'Razao', 'LocalEnt'))
												->setWhere("RepCod = '{$this->db->escape($repcod)}' AND Apelido LIKE '%{$this->db->escape($apelido)}%'")
												->select();
		}
		
		public function getCliente($campo, $valor, Array $resultados = array('*'))
		{
				return $this->db->setTable('dbo.Cliente')
												->setFields($resultados)
												->setWhere("{$campo} = '{$this->db->escape($valor)}'")
												->select();
		}
}