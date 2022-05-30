<?php 

function cashCategoryLabel($string) {
    if (str_contains($string, 'product_sales')) {
        $result = 'Product Sales';
    } else if (str_contains($string, 'petty_cash')) {
        $result = 'Kas Kecil';
    } else if (str_contains($string, 'expense')) {
        $result = 'Pengeluaran';
    } else if (str_contains($string, 'transfer')) {
        $result = 'Setoran / Transfer';
    } else {
        $result = 'Lainnya';
    }
	return $result;
}

?>