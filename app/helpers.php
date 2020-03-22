<?php
function orderNumber($order_id) {
  return str_pad($order_id, 5, "0", STR_PAD_LEFT);
}

function money($price) {
	return '₱' . number_format($price, 2, '.', '');	
}

function isSelected($current_value, $selected_value){
    if($current_value == $selected_value){
        return 'selected';
    }
}