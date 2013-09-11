<?php
class Request
{
		private $request = array();
		
		public function __construct()
		{
				$this->request['post']	= $_POST;
				$this->request['get']		= $_GET;
				$this->request['self']	= $_REQUEST;
		}
		
		public function __get($key)
		{
				if (!array_key_exists($key, $this->request)) die("Request: '$key' não existe.");
				return $this->request[$key];
		}
}