<?php 

function priceTypeLabel($string) {
    if (str_contains($string, 'consument')) {
        $result = 'Consument';
    } else if (str_contains($string, 'retail')) {
        $result = 'Retail';
    } else if (str_contains($string, 'ws')) {
        $result = 'Whole';
    } else if (str_contains($string, 'sws')) {
        $result = 'Sub Whole';
    } else {
        $result = 'Depo';
    }
	return $result;
}

?>