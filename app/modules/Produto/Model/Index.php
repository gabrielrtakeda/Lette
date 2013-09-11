<?php
class Produto_IndexModel extends Model
{
		public function getProdutos()
		{
				return $this->db->query("SELECT * FROM dbo.Produto", 15);
		}
		public function getProduto($codProd)
		{
				// return
		}
		public function getCores($idFirma = 1, $codProd)
		{
				return $this->db->query("SELECT * FROM dbo.ProCorFirma WHERE IDFirma = {$idFirma} AND Codigo = '{$codProd}';");
		}
}