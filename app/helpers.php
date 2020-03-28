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

function isChecked($current_value, $wanted_values = [1, 'on']) {
  if (in_array($current_value, $wanted_values, true)) {
    return 'checked';
  }
  return '';
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
    if ($datetime) {
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format('D, M n, Y [h:i A]');
    }
}