<?php
class Controller extends System
{
		public $data		= array();
		public $_favicon;
		public $_js			= array();
		public $_css		= array();
		public $_childs	= array();
		
		public function __construct()
		{
				parent::__construct();
				$this->addFavicon('Lette-Favicon.png');
				
				$this->addJs('jquery/jquery-1.10.2.min.js');
				$this->addJs('jquery/jquery-ui.min.js');
				$this->addJs('bootstrap/bootstrap.js');
				$this->addJs('easytooltip/easyTooltip.js');
				$this->addJs('hikari.js');
				$this->addJs('common.js');
				
				$this->addStyle('bootstrap/bootstrap.css');
				$this->addStyle('font-face.css');
				$this->addStyle('style.css');
		}
		
		public function addJs($src, $external = null)
		{
				$this->_js[] = '<script type="text/javascript" src="'. (!$external ? DIR_JS : '') . $src .'"></script>';;
		}
		
		public function addLocalJs($module, $src)
		{
				$this->_js[] = '<script type="text/javascript" src="'. $this->url->getDirView($module, false) .'js/'. $src .'"></script>';
		}
		
		public function addStyle($href, $media = null)
		{
				$this->_css[] = '<link rel="stylesheet" type="text/css" href="'. DIR_CSS . $href .'"'. ($media ? ' media="'. $media .'"' : '') .' />';
		}
		
		public function addFavicon($file)
		{
				$this->_favicon = '<link rel="icon" type="image/png" href="'. DIR_IMAGES . $file .'" />';
		}
		
		public function getFavicon()
		{
				return $this->_favicon;
		}
		public function getJs()
		{
				return $this->_js;
		}
		
		public function getStyles()
		{
				return $this->_css;
		}
		
		/**
		 * Fragmenta o parâmetro passado ('Module'_'Controller'_'Action') e retorna objeto com as definições de cada.
		 */
		public function boom($module_controller_action)
		{
				$boom = explode('_', $module_controller_action);
				
				$result = (object) array();
				$result->module			= $boom[0];
				$result->controller	= (isset($boom[1]) ? $boom[1] : 'index');
				$result->action			= (isset($boom[2]) ? $boom[2] : 'index') . 'Action';
				$result->view				= (object) array();
				$result->view->name		= $result->controller;
				$result->view->file		= $result->controller . '.phtml';
				$result->class			= ucfirst($result->module) .'_'. ucfirst($result->controller . 'Controller');
				$result->uc							= (object) array();
				$result->uc->module			= ucfirst($result->module);
				$result->uc->controller	= (object) array();
				$result->uc->controller->name		= ucfirst($result->controller . 'Controller');
				$result->uc->controller->file		= ucfirst($result->controller . 'Controller') . '.php';
				
				return $result;
		}
		
		public function isLogged()
		{
				if (!($this->session->has('login'))) {
						$this->addLocalJs('default', 'login.js');
						$this->render('default_login');
						exit();
				} else return true;
		}
		
		public function isNull($string)
		{
				return (!is_null($string) ? $string : '-');
		}
		
		public function renderChilds()
		{
				// Armazena o conteúdo dos 'childs' em uma variável
				$this->_childs[]	= 'default_head';
				$this->_childs[]	= 'default_header';
				$this->_childs[]	= 'default_footer';
				
				$firma = $this->getModel('default_firma');
				$this->data['firmas'] = $firma->getFirmas();
				
				if ((is_array($this->data)) && (count($this->data) > 0))
						extract($this->data);
						
				if ($this->session->has('login')) $this->_childs[] = 'default_welcome';
				if (!empty($this->_childs)) {
						foreach ($this->_childs as $key => $child) {
								$boom = $this->boom($child);
								$nk		= (gettype($key) == 'string' ? $key : $boom->controller);
								ob_start();
										require_once($this->url->getDirView($boom->module) . $boom->view->file);
										$this->data[$nk] = ob_get_contents();
								ob_end_clean();
						}
				}
		}
		
		protected function render($module_controller)
		{
				$this->renderChilds();
				
				if ((is_array($this->data)) && (count($this->data) > 0))
						extract($this->data);
				
				$boom		= $this->boom($module_controller);
				
				if (!is_dir($this->url->getDirModule($boom->module)))
						die("O módulo [{$boom->module}] não existe!");
						
				if (!file_exists($this->url->getDirView($boom->module) . $boom->view->file))
						die("A arquivo [{$boom->view->file}] não existe no módulo [$module]!");
						
				// Inclui o arquivo 'view' do 'controller'
				require ($this->url->getDirView($boom->module) . $boom->view->file);
		}
}