<?php
class Numbers
{
		public function zeroMask($number, $length, $char = 0) {
				$new = '';
				if (strlen($number) < $length) {
						for ($i = 0; $i < ($length - strlen($number)); $i++) {
								$new .= (string) $char;
						}
				}
				$new .= (string) $number;
				return $new;
		}
}