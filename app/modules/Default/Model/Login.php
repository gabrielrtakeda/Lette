<?php
class Default_LoginModel extends Model
{
		public function userLogin($uid, $pwd)
		{
				return $this->db->query("SELECT * FROM dbo.Rep WHERE Apelido='{$uid}' AND WebSenha='{$pwd}'");
		}
		
		public function getDefaultFirma()
		{
				return $this->db->query("SELECT TOP 1 * FROM dbo.Firma;")->FETCH_OBJECT[0];
		}
		
		public function compare(Array $values)
		{
				$fields = array_keys($values);
				$values = array_values($values);
				
				$where	= '';
				for ($i = 0; $i < count($fields); $i++) {
						$where .= $fields[$i] ."='". $values[$i] ."'". ($i != (count($fields) - 1) ? ' AND ' : '');
				}
				
				$compare = $this->db	->setTable('dbo.Rep')
															->setFields($fields)
															->setWhere($where)
															->select();
				
				return ($compare->NUM_ROW > 0 ? true : false);
		}
}