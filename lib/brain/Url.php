<?php
class Url
{
		public function getDirModule($module)
		{
				return DIR_MODULES . ucfirst($module) . '\\';
		}
		
		public function getDirController($module)
		{
				$boom = explode('_', $module);
				if (count($boom) < 1) die('Defina corretamente [Module]_[Controller] para Url::getDirController()');
				$module			= ucfirst($boom[0]);
				$controller	= ucfirst((isset($boom[1]) ? $boom[1] : 'index'));
				return DIR_MODULES . $module . '\Controller\\';
		}
		
		public function getDirView($module, $escaped = true)
		{
				return ($escaped ? DIR_MODULES . ucfirst($module) . '\View\\' : DIR_MODULES_NE . ucfirst($module) . '/View/');
		}
		
		public function getDirModel($module)
		{
				return DIR_MODULES . ucfirst($module) . '\Model\\';
		}
		
		public function redirect($module_controller_action, $redir = true)
		{
				$boom = explode('_', $module_controller_action);
				if (count($boom) < 1) die('Defina correamente [Module]_[Controller]_[Action] para Url::redir()');
				$module			= $boom[0];
				$controller	= (isset($boom[1]) ? '/' . $boom[1] : '');
				$action			= (isset($boom[2]) ? '/' . $boom[2] : '');
				
				$path = $module . $controller . $action;
				
				if ($redir) header("Location: " . BASE_URL . $path);
				else return BASE_URL . $path;
		}
}