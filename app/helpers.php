<?php
use Carbon\Carbon;

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

function diffForHumans($datetime) {
	return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->diffForHumans();
}

// TODO: maybe this fits somewhere else?
function filterByStatus($data, $status) {
	$filtered = collect($data->toArray())->filter(function ($value, $key) use ($status) {
    	info($value['status'] . " : " . $status);
    	return $value['status'] == $status;
	});
	return $filtered->count();
}

function friendlyDatetime($datetime) {
    return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format('D, M n, Y [h:i A]');
}