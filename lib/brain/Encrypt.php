<?php
class Encrypt
{
		public function sha512($string)
		{
				return hash('sha512', $string);
		}
}