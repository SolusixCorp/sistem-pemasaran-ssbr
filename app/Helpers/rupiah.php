<?php 

function rupiah($numbers, $symbol) {
    if ($symbol == TRUE) {
        $result = "Rp. " . number_format($numbers, 2, ',', '.');
    } else {
        $result = number_format($numbers, 2, ',', '.');
    }
	return $result;
}

?>