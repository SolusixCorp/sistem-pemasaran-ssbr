<?php 

function numberToDate($number) {
	return substr($number, 0, 4) . '-' .  substr($number, 4, 2) . '-' .substr($number, 6, 2) . ' ' . substr($number, 8, 2) . ':' . substr($number, 10, 2) . ':' . substr($number, 12, 2);
}

?>