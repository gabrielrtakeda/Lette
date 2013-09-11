<?php
class System extends Registry
{
		protected $_url;
		protected $_explosion;
		
		protected $_module			= array();
		protected $_controller	= array();
		protected $_action			= array();
		protected $_params			= array();
		
		public function __construct()
		{
				parent::__construct();
				$this->setUrl();
				$this->setExplosion();
				$this->setModule();
				$this->setController();
				$this->setAction();
				$this->setParams();
		}
		
		private function setUrl()
		{
				$this->_url = (isset($_GET['url']) ? $_GET['url'] : 'default/index/index');
		}
		
		private function setExplosion()
		{
				$dynamite = explode('/', $this->_url);
				foreach ($dynamite as $boom) {
						if ($boom) $this->_explosion[] = $boom;
				}
		}
		
		private function setModule()
		{
				$this->_module['path'] = DIR_MODULES . (isset($this->_explosion[0]) ? ucfirst($this->_explosion[0]) : 'Default');
				$this->_module['name'] = (isset($this->_explosion[0]) ? ucfirst($this->_explosion[0]) : 'Default');
		}
		
		private function setController()
		{
				$this->_controller['path'] = $this->_module['path'] .'\Controller\\'. (isset($this->_explosion[1]) ? ucfirst($this->_explosion[1]) : 'Index') . 'Controller.php';
				$this->_controller['name'] = (isset($this->_explosion[1]) ? $this->_explosion[1] : 'index');
				$this->_controller['class'] = (isset($this->_explosion[0]) ? ucfirst($this->_explosion[0]) : 'Default') .'_'. (isset($this->_explosion[1]) ? ucfirst($this->_explosion[1]) : 'Index') . 'Controller';
		}
		
		private function setAction()
		{
				$this->_action['name'] = (isset($this->_explosion[2]) ? $this->_explosion[2] : 'index');
				$this->_action['method'] = (isset($this->_explosion[2]) ? $this->_explosion[2] : 'index') . 'Action';
		}
		
		private function setParams()
		{
				foreach ($this->_explosion as $key => $param) {
						if ($key > 2) $this->_params[] = $param;
				}
		}
		
		protected function getParams(Array $keys)
		{
				if (!is_array($keys)) die('O parâmetro passado deve ser do tipo "Array".');
				$params = $this->_params;
				if (count($keys) != count($params)) die('O número de parâmetros não coincide com a quantidade permitida.');
				return array_combine($keys, $params);
		}
		
		protected function countParams()
		{
				return count($this->_params);
		}
		
		public function getModel($path)
		{
				$boom = explode('_', $path);
				if (count($boom) < 1) die('Class: Controller, Method: getModel() ### - Erro de parâmetro.');
				$module			= ucfirst($boom[0]);
				$controller	= ucfirst((isset($boom[1]) ? $boom[1] : 'index'));
				$model_path = $this->url->getDirModel($boom[0]) . ucfirst($boom[1]) . '.php';
				
				if (!file_exists($model_path)) die('Class: Controller, Method: getModel() ### - Model ['. ucfirst($boom[1]) .'] não existe.');
				require_once($model_path);
				$model = ucfirst($boom[0]) . '_' . ucfirst($boom[1]) . 'Model';
				
				return new $model();
		}
		
		public function run()
		{
				if (!file_exists($this->_controller['path'])) {
						die('O controller "' . ucfirst($this->_controller['name']) . '.php" não existe!');
				}
				require_once($this->_controller['path']);
				$class = new $this->_controller['class']();
				$action = $this->_action['method'];
				
				if (!method_exists($class, $action)) {
						header("Location: /localhost/lette");
				}
				if (method_exists($class, '_init')) {
						$class->_init();
				}
				$class->$action();
		}
		
		
}