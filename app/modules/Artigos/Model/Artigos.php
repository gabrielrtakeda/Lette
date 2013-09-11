<?php
class Artigos_ArtigosModel extends Model
{
		public function getArtigos($limit = 0, $page = 1)
		{
				$this->session->set('artigos', array('page' => $page));
				if (!$limit)
						return $this->db->query("SELECT * FROM dbo.Artigos ORDER BY Artigo ASC;");
				else
						return $this->db->setTable('dbo.Artigos')->setOrder('Artigo ASC')->setLimit($limit)->setPage($page)->select();
		}
		
		public function getNumRow()
		{
				return $this->db->setTable('dbo.Artigos')
												->setFields(array('COUNT(ID) as NumRow'))
												->select();
		}
}