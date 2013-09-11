<?php
class Default_FirmaModel extends Model
{
		public function getFirmas()
		{
				return $this->db->setTable('dbo.Firma')->setFields(array('IDFirma', 'CNPJ', 'Fantasia', 'Razao'))->select();
		}
		
		public function getFirma($idFirma)
		{
				return $this->db->setTable('dbo.Firma')->setFields(array('IDFirma', 'CNPJ', 'Fantasia', 'Razao'))->setWhere("IDFirma = {$idFirma}")->select()->FETCH_OBJECT[0];
		}
}