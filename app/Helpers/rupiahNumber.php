<?php 

function rupiahNumber($string) {
    $string = str_replace('Rp. ', '', $string);
    $string = str_replace(',00', '', $string);
    $string = str_replace('.', '', $string);
    $string = str_replace(',', '', $string);
	return $string;
}

?>