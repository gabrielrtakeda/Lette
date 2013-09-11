<?php
class Registry
{
		public $_data = array();
		
		public function __construct()
		{
				chdir(DIR_BRAIN_ESC);
				foreach (glob('*.php') as $file) {
						$filename = str_replace('.php', '', $file);
						require_once($file);
						$this->_data[strtolower($filename)] = new $filename();
				}
		}
		
		public function __set($name, $value)
		{
				echo "Setting '$name' to '$value'<br>";
				$this->_data[$name] = $value;
		}
		
		public function __get($name)
		{
				if (array_key_exists($name, $this->_data)) {
						return $this->_data[$name];
				}
		}
}