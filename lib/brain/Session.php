<?php
class Session
{
		public function get($key = 'all')
		{
				if ($key == 'all') return $_SESSION;
				else {
						if (isset($_SESSION[$key]))
								return $_SESSION[$key];
						else
								return die("Session: '$key' não existe.");
				}
		}
		
		public function set($key, $value)
		{
				return $_SESSION[$key] = $value;
		}
		
		public function setChilds($keys, $value)
		{
				$boom			= explode('->', $keys);

				$v =& $_SESSION;
				foreach ($boom as $route) {
						 $v =& $v[$route];
				}
				$v = $value;
		}
		
		public function add($key, $value)
		{
				if (!$this->has($key)) $_SESSION[$key] = $value;
				array_push($_SESSION[$key], $value);
		}
		
		public function has($key)
		{
				return isset($_SESSION[$key]);
		}
		
		public function delete($key)
		{
				if (isset($_SESSION[$key]))
						unset($_SESSION[$key]);
				else
						return die("Session: '$key' não existe.");
		}
		
		public function clear()
		{
				foreach ($this->get() as $k => $session) {
						var_dump($k);
						$this->delete($k);
				}
		}
}