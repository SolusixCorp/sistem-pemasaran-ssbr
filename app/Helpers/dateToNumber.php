<?php 

function dateToNumber($date) {
	return str_replace(array('-', ':', ' '), '', $date);
}

?>