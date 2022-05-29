<?php 

function priceType($string) {
    if (str_contains($string, 'Consument')) {
        $result = 'consument';
    } else if (str_contains($string, 'Retail')) {
        $result = 'retail';
    } else if (str_contains($string, 'Whole')) {
        $result = 'ws';
    } else if (str_contains($string, 'Sub Whole')) {
        $result = 'sws';
    } else {
        $result = 'depo_price';
    }
	return $result;
}

?>