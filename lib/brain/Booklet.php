<?php
class Booklet
{
		private $pageNumber;
		private $currentPage;
		
		public function setPageNumber($numRow, $limit)
		{
				$this->pageNumber = ceil($numRow / $limit);
				return $this;
		}
		
		public function getPageNumber()
		{
				return $this->pageNumber;
		}
		
		public function setCurrentPage($page)
		{
				$this->currentPage = $page;
				return $this;
		}
		
		public function getCurrentPage()
		{
				return $this->currentPage;
		}
}