<?php
class Order_OrderModel extends Model
{
		public function consultaOrder($campo, $valor)
		{
				return $this->db->setTable('dbo.PreOrder')
												->setFields(array('Material', 'PO', 'Previsao'))
												->setWhere("{$campo} LIKE '%{$this->db->escape($valor)}%'")
												->setOrder("{$campo} ASC")
												->select();
		}
}