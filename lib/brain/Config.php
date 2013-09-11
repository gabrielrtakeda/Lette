<?php
class Config
{
		public function __get($name)
		{
				switch ($name) {
										case 'module'			: $sys = new System(); return $sys->_module['name'];
						break;	case 'controller'	: $sys = new System(); return $sys->_controller['name'];
						break;	case 'action'			: $sys = new System(); return $sys->_action['name'];
						break;	default : die("Url: '$name' não existe!");
				}
		}
}