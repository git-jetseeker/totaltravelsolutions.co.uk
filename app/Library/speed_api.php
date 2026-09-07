<?php


function speedpark_companies_call($entry_date='',$exit_date=''){
	//$postfields = array('token'=>"c8c38cc5fb7ccdf864165d60b617655f2667ccbf", 'start'=>"2018-02-20 09:00", 'end'=>"2018-02-28 09:00");
	$postfields = array('token'=>"c8c38cc5fb7ccdf864165d60b617655f2667ccbf", 'start'=>$entry_date, 'end'=>$exit_date);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://speedpark.co.uk/api/v2/availability/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // On dev server only!
	$result = curl_exec($ch);
	$result = json_decode($result);
	$result = object_to_array($result);
	return $result;
}


function get_speedpark_companies ($results){
	global $db;
	$speed_park_companies = array();
	foreach($results as $row){
		if($row['spaces_available'] >0){
			$escape = $db ->real_escape($row['site_name']);
			$speed_park_company = $db->get_row("SELECT * FROM " . $db->prefix . "companies where name = '".$escape."' && Is_active= 'Yes'");
			if($speed_park_company){
				$speed_park_company['price'] = $row['parking_cost']; 
				$speed_park_company['companyID'] = $speed_park_company['id'];
				$speed_park_company['speed_park_active'] = 1;
				$speed_park_company['site_codename'] = $row['site_codename'];
				$speed_park_companies[] = $speed_park_company;
			}
		}
	}
	return $speed_park_companies;
}
  
 function get_speed_data($dropdate,$pickdate,$dropoftime,$pickuptime){
	$speedArrivalDate = date('Y-m-d', strtotime($dropdate));
	$speedDepartDate = date('Y-m-d', strtotime($pickdate));
	$speedArrivalTime = date("H:i",strtotime($dropoftime));
	$speedDepartTime = date("H:i",strtotime($pickuptime));
	$speed_dropdate = $speedArrivalDate." ".$speedArrivalTime;
	$speed_pickdate = $speedDepartDate." ".$speedDepartTime;
	$speed_result = speedpark_companies_call($speed_dropdate,$speed_pickdate);
	$speed_companies = get_speedpark_companies($speed_result);
	return $speed_companies;
 }
 
 function get_speed_single($site_codename,$dropdate,$pickdate,$dropoftime,$pickuptime){
	$speed_all_companies = get_speed_data($dropdate,$pickdate,$dropoftime,$pickuptime);
	$data_results = array_search($site_codename, array_column($speed_all_companies, 'site_codename'));
	$single_price = $speed_all_companies[$data_results]['price'];
	return $single_price;
 }
function creat_speed_booking_call($entry_date,$exit_date,$site_codename,$fullname,$registration,$reff_NO){
	$postfields = array('token'=>"c8c38cc5fb7ccdf864165d60b617655f2667ccbf", 'start'=>$entry_date, 'end'=>$exit_date,'site'=>$site_codename,'name'=>$fullname,'registration'=>$registration,'people_travelling'=>1,'reference'=>$reff_NO);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://speedpark.co.uk/api/v2/book/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // On dev server only!
	$result = curl_exec($ch);
	$result = json_decode($result);
	$result = object_to_array($result);
	return $result;
}
function creat_speed_booking($dropdate,$pickdate,$dropoftime,$pickuptime,$site_codename,$fullname,$registration,$reff_NO){
	$speedArrivalDate = date('Y-m-d', strtotime($dropdate));
	$speedDepartDate = date('Y-m-d', strtotime($pickdate));
	$speedArrivalTime = date("H:i",strtotime($dropoftime));
	$speedDepartTime = date("H:i",strtotime($pickuptime));
	$speed_dropdate = $speedArrivalDate." ".$speedArrivalTime;
	$speed_pickdate = $speedDepartDate." ".$speedDepartTime;
	$speed_result_booking = creat_speed_booking_call($speed_dropdate,$speed_pickdate,$site_codename,$fullname,$registration,$reff_NO);
	return $speed_result_booking;
}
 
?>