<?php

namespace App\Library;

class functions

{

    function get_client_ip()

    {

        $ipaddress = '';

        if (getenv('HTTP_CLIENT_IP'))

            $ipaddress = getenv('HTTP_CLIENT_IP');

        else if (getenv('HTTP_X_FORWARDED_FOR'))

            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');

        else if (getenv('HTTP_X_FORWARDED'))

            $ipaddress = getenv('HTTP_X_FORWARDED');

        else if (getenv('HTTP_FORWARDED_FOR'))

            $ipaddress = getenv('HTTP_FORWARDED_FOR');

        else if (getenv('HTTP_FORWARDED'))

            $ipaddress = getenv('HTTP_FORWARDED');

        else if (getenv('REMOTE_ADDR'))

            $ipaddress = getenv('REMOTE_ADDR');

        else

            $ipaddress = 'UNKNOWN';

        return $ipaddress;

    }



    function insert_user_track($ref_src, $ref_url, $url)

    {

        global $db;

        date_default_timezone_set("Europe/London");

        $date = date("d-m-Y h:i:sa");

        $agent = 3;

        $ip = get_client_ip();

        $sql = $db->insert("INSERT INTO `fpp_tracking` ( `ip`, `agent`, `date`, `ref_url`, `ref_src`,`url`) VALUES ( '$ip', '$agent', '$date', '$ref_url','$ref_src','$url')");

    }



    function get_company_levy($company_id)

    {

        global $db;

        $company_levy = $db->get_row("SELECT extra_charges FROM " . $db->prefix . "companies WHERE  id = '" . $company_id . "'");

        return $company_levy['extra_charges'];

    }



    function redirect($url)

    {

        echo '<script language="javascript" type="text/javascript">';

        echo 'window.location = "' . $url . '"';

        echo '</script>';

    }



    function addDayswithdate($date, $days)

    {

        $date = strtotime("+" . $days . " days", strtotime($date));

        return date("m/d/Y", $date);

    }



    function Search_IN_ARRAY($array, $key, $value)

    {

        $results = array();



        if (is_array($array)) {

            if (isset($array[$key]) && $array[$key] == $value) {

                $results[] = $array;

            }



            foreach ($array as $subarray) {

                $results = array_merge($results, Search_IN_ARRAY($subarray, $key, $value));

            }

        }



        return $results;

    }



    function send_sms($number, $ref)

    {

        $url = "www.voodooSMS.com/vapi/server/sendSMS";

        $getString = "?dest=" . $number . "&";

        $getString .= "orig=TTS&";

        $getString .= "msg=" . urlencode("Thank you for choosing Total Travel Solutions. Your booking with Ref# " . $ref . " is confirmed, for arrival & departure procedure please check your email.") . "&";

        $getString .= "pass=w2imbg6&";

        $getString .= "uid=flyparkplus&";

        $getString .= "validity=1&";

        $getString .= "format=xml&";

        $getString .= "cc=44";



        $ch = curl_init();

        //set the url, GET data

        curl_setopt($ch, CURLOPT_URL, $url . $getString);

        curl_setopt($ch, CURLOPT_HTTPGET, 1); //default

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        //execute post

        $xml = curl_exec($ch);

        $xml = simplexml_load_string($xml);

        $result = json_decode(json_encode((array)$xml));

        return $result->result;

        curl_close($ch);

    }
    
    function incomplete_sms($number, $link)

    {

        $url = "www.voodooSMS.com/vapi/server/sendSMS";

        $getString = "?dest=" . $number . "&";

        $getString .= "orig=TTS&";

        $getString .= "msg=" . urlencode("We have your incomplete booking, please follow link to complete the booking " . $link ) . "&";

        $getString .= "pass=w2imbg6&";

        $getString .= "uid=flyparkplus&";

        $getString .= "validity=1&";

        $getString .= "format=xml&";

        $getString .= "cc=44";



        $ch = curl_init();

        //set the url, GET data

        curl_setopt($ch, CURLOPT_URL, $url . $getString);

        curl_setopt($ch, CURLOPT_HTTPGET, 1); //default

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        //execute post

        $xml = curl_exec($ch);

        $xml = simplexml_load_string($xml);

        $result = json_decode(json_encode((array)$xml));

        return $result->result;

        curl_close($ch);

    }



    function multisort(&$array, $key)

    {

        $valsort = array();

        $ret = array();

        reset($array);

        foreach ($array as $ii => $va) {

            $valsort[$ii] = $va[$key];

        }

        asort($valsort);

        foreach ($valsort as $ii => $va) {

            $ret[$ii] = $array[$ii];

        }

        $array = $ret;

    }



    function sort_filter($array, $on, $order = 'asc')

    {

        $new_array = array();

        $sortable_array = array();

        if (count($array) > 0) {

            foreach ($array as $k => $v) {

                if (is_array($v)) {

                    foreach ($v as $k2 => $v2) {

                        if ($k2 == $on) {

                            $sortable_array[$k] = $v2;

                        }

                    }

                } else {

                    $sortable_array[$k] = $v;

                }

            }

            switch ($order) {

                case 'asc':

                    asort($sortable_array);

                    break;

                case 'desc':

                    arsort($sortable_array);

                    break;

            }

            foreach ($sortable_array as $k => $v) {

                array_push($new_array, $array[$k]);

            }

        }

        return $new_array;

    }





    function curl_call($url)

    {

        $ch = curl_init();

        $timeout = 5;

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;

    }



    function checkUser($userData = array())

    {

        global $db;

        if (!empty($userData)) {

            //Check whether user data already exists in database

            $userData1 = $db->get_row("SELECT * FROM " . $db->prefix . "customer WHERE oauth_provider = '" . $userData['oauth_provider'] . "' AND email = '" . $userData['email'] . "'");

            if ($userData1 > 0) {

                //Update user data if already exists

                $query = $db->update("UPDATE " . $db->prefix . "customer SET first_name = '" . $userData['first_name'] . "', last_name = '" . $userData['last_name'] . "', email = '" . $userData['email'] . "', update_on = '" . date("Y-m-d H:i:s") . "' WHERE oauth_provider = '" . $userData['oauth_provider'] . "' AND oauth_uid = '" . $userData['oauth_uid'] . "'");

            } else {

                //Insert user data

                $query = $db->insert("INSERT INTO " . $db->prefix . "customer SET oauth_provider = '" . $userData['oauth_provider'] . "', oauth_uid = '" . $userData['oauth_uid'] . "', first_name = '" . $userData['first_name'] . "', last_name = '" . $userData['last_name'] . "', email = '" . $userData['email'] . "', added_on = '" . date("Y-m-d H:i:s") . "', update_on = '" . date("Y-m-d H:i:s") . "'");

            }



            $userData1 = $db->get_row("SELECT * FROM " . $db->prefix . "customer WHERE oauth_provider = '" . $userData['oauth_provider'] . "' AND email = '" . $userData['email'] . "'");

        }



        //Return user data

        return $userData1;

    }



    function is_decimal($val)

    {

        return is_numeric($val) && floor($val) != $val;

    }



    function book_time($id)

    {

        global $db;

        $result = $db->get_row("SELECT createdate FROM " . $db->prefix . "booking WHERE companyId = '" . $id . "' order by createdate  desc limit 1");

        return $result['createdate'];

    }



    function time_ago($timestamp)

    {

        $time_ago = strtotime($timestamp);

        $current_time = time();

        $time_difference = $current_time - $time_ago;

        $seconds = $time_difference;

        $minutes = round($seconds / 60);           // value 60 is seconds

        $hours = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec

        $days = round($seconds / 86400);          //86400 = 24 * 60 * 60;

        $weeks = round($seconds / 604800);          // 7*24*60*60;

        $months = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60

        $years = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60

        if ($seconds <= 60) {

            return "Booked Just Now";

        } else if ($minutes <= 60) {

            if ($minutes == 1) {

                return "Last booked one min ago";

            } else {

                return "Last booked $minutes min ago";

            }

        } else if ($hours <= 24) {

            if ($hours == 1) {

                return "Last booked an hour ago";

            } else if ($hours <= 12) {

                return "Last booked More then 61 minutes ago";

            }

        }

        // else if($days <= 7)

        // {

        // if($days==1){

        // return "Last booked yesterday";

        // }

        // else{

        // return "Last booked $days days ago";

        // }

        // }

        else {

            if ($weeks == 1) {

                return "Last booked More then 61 minutes ago";

            } else {

                return "Last booked More then 61 minutes ago";

            }

        }



    }



    function custom_date($date, $hour = 0)

    {

        if ($hour == 0) {

            $date = explode("/", $date);

            $date = $date[2] . "-" . $date[0] . "-" . $date[1];

        } else {

            $date = explode("/", $date);

            $time = explode(" ", $date[2]);

            $date = $time[0] . "-" . $date[0] . "-" . $date[1];

            $time = $time[1];

            $date = array('date' => $date, 'time' => $time);

        }

        return $date;

    }



    function facebooklogin($url = '')

    {

        $facebook = new Facebook(array('appId' => fb_id, 'secret' => fb_secret));

        $fbUser = $facebook->getUser();

        if (!$fbUser) {

            $fbUser = NULL;

            $loginURL = $facebook->getLoginUrl(array('redirect_uri' => fb_url, 'scope' => fb_permissions));

            echo '<a class="btn btn-submit btn-facebook" href="' . $loginURL . '"><span class="fa fa-facebook"></span> Sign in with Facebook</a>';

        } else {

            //Get user profile data from facebook

            $fbUserProfile = $facebook->api('/me?fields=id,first_name,last_name,email');

            //Insert or update user data to the database

            $fbUserData = array(

                'oauth_provider' => 'facebook',

                'oauth_uid' => $fbUserProfile['id'],

                'first_name' => $fbUserProfile['first_name'],

                'last_name' => $fbUserProfile['last_name'],

                'email' => $fbUserProfile['email']

            );

            $userData = checkUser($fbUserData);



            if ($userData > 0) {

                $_SESSION['user_name'] = $userData['first_name'] . " " . $userData['last_name'];

                $_SESSION['is_login'] = 'Yes';

                $_SESSION['login_id'] = $userData['id'];

                $_SESSION['login_email'] = $userData['email'];

                if ($url > 0) {

                    redirect(SITE_ADDRESS . $url);

                } else {

                    redirect(SITE_ADDRESS);

                };

                //$userData

            } else {

                echo '<script>alert("Please provide valid email and password")</script>';

            }

        }

    }



    function get_post($ID = '')

    {

        global $db;

        if (!empty($ID) && is_numeric($ID)) {

            $this_query = " ID = " . $ID;

        } elseif (empty($ID) && isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {

            $this_query = " ID = " . $_GET['post_id'];

        } else {

            $this_query = " slug = '$ID'";

        }



        //Get post by id

        $get_post = $db->get_row("select * from " . $db->prefix . "pages where " . $this_query . " and status = 'Yes'");

        return $get_post;

    }



    function get_airport()

    {

        global $db;

        $airports = $db->select("SELECT * FROM " . $db->prefix . "airports WHERE status = 'Yes' ORDER BY airport_name ASC");

        foreach ($airports as $airport) {

            echo '<option value="' . $airport['id'] . '">' . $airport['airport_name'] . '</option>';

        }

    }



    function get_room_type()

    {

        global $db;

        $room_types = $db->select("SELECT * FROM " . $db->prefix . "room_type");

        foreach ($room_types as $room_type) {

            echo '<option value="' . $room_type['id'] . '">' . $room_type['room_type'] . '</option>';

        }

    }



    function get_room_type_name($id)

    {

        global $db;

        $room_types = $db->select("SELECT * FROM " . $db->prefix . "room_type where id= " . $id);

        echo $room_types['room_type'];

    }



    function checkSubscribers($ip)

    {

        global $db;

        return $rows = $db->count_row("SELECT * FROM " . $db->prefix . "subscribers where ip= '" . $ip . "' ");

    }



    function getPlanPrice($companyid, $dropdate, $no_of_days)

    {

        global $db;

        $selected_date = strtotime($dropdate);

        $year = date('Y', $selected_date);

        $month = date('m', $selected_date);

        $day = date('d', $selected_date);

        $priceplan = $db->select("SELECT * FROM " . $db->prefix . "airport_price_plan WHERE company_id = '" . $companyid . "' and year = '" . $year . "' and month = '" . $month . "' ");

        foreach ($priceplan as $priceplan) {

            $planid = $priceplan['id'];

        }

        $sel_day = 'day_' . $day;

        $select_brand = $db->select("SELECT * FROM " . $db->prefix . "airport_select_brand WHERE plan_id = '" . $planid . "' and day_no = '" . $sel_day . "' ");

        foreach ($select_brand as $brand) {

            $plan_brand = $brand['brand'];

        }

        if ($no_of_days < 30) {

            $total_days = 'day_' . $no_of_days;

        } else {

            $total_days = 'day_30';

        }

        $select_amount = $db->select("SELECT * FROM " . $db->prefix . "airport_price_brands WHERE id = '" . $planid . "' ");

        foreach ($select_amount as $brand_amount) {

            $amount = $brand_amount['day_' . $no_of_days];

        }

        return $no_of_days . '---' . $total_days . '-----' . $planid . '------' . $plan_brand . '----' . $amount . 'SELECT * FROM " fpp_airport_price_brands WHERE id = ' . $planid . ' "';

    }



    function priceFormat($price, $symbol = true)

    {

        $formated_price = '';

        if ($symbol) {

            $formated_price .= '&pound;';

        }

        $formated_price .= number_format(($price * 1), 2);

        return $formated_price;

    }



    function generate_password()

    {

        $code = "";

        $chars = 8;

        $letters = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ#@!*^%$_()abcdefghijklmnopqrstuvwxyz';

        $code = substr(str_shuffle($letters), 0, $chars);

        return $code;

    }



    function getPromoDiscount($promoCode, $booking_price, $type = '', $company_id = '')

    {

        global $db;

        if ($promoCode == '') {

            return 0.00;

        }

        $book_type = "";



        if ($type == 'airport_parking') {

            $book_type = 'airport_parking';

        }

        if ($type == 'airport_hotel') {

            $book_type = 'airport_hotel';

        }

        if ($type == 'airport_hotel_parking') {

            $book_type = 'airport_hotel_parking';

        }

        if ($type == 'airport_lounges') {

            $book_type = 'airport_lounges';

        }

        // if(!$_COOKIE['traffic']){

        // $discount_for = 'Og';

        // }

        // else{

        $Discount_filter = '';

        if ($_COOKIE['traffic'] == 'AF') {

            $discount_for = $_COOKIE['traffic'];

            $Discount_filter = "AND discount_for = '" . $discount_for . "'";

        }

        // $discount_for = 'FB';

        // }





        $_current_time = $db->php_now();

        $days_ago = date('d-m-Y H:i:s', strtotime('-2 days', strtotime($_current_time)));



        $ip = get_client_ip();

        $booking_src = $db->get_row("SELECT * FROM " . $db->prefix . "tracking WHERE ip = '" . $ip . "' && date > '" . $days_ago . "' order by id DESC");

        if ($booking_src) {

            if ($booking_src['ref_src'] == '') {

                $booking_src1 = $db->get_row("SELECT * FROM " . $db->prefix . "tracking WHERE ip = '" . $ip . "' && ref_src !='' && date > '" . $days_ago . "' order by id DESC");

                if ($booking_src1) {

                    $_traffic_src = $booking_src1['ref_src'];

                } else {

                    $_traffic_src = $booking_src['ref_src'];

                }

            } else {

                $_traffic_src = $booking_src['ref_src'];

            }

        } else {

            $_traffic_src = $_COOKIE['traffic'];

        }





        $promoDetails = $db->get_row("SELECT * FROM " . $db->prefix . "discount 

                            WHERE promo = '" . $promoCode . "' AND status = 'Yes' AND parking_type = '" . $book_type . "' 

							$Discount_filter  AND start_date <= '" . date("Y-m-d") . "' AND end_date >= '" . date("Y-m-d") . "'");

        if ($_traffic_src == "PPC") {

            if ($promoDetails['discount_for'] == 'PPC') {

                $promoDetails['discount_value'] = $promoDetails['discount_value'];

            } else {

                $promoDetails['discount_value'] = '2.00';

            }

        }





        if ($promoDetails['discount_type'] == 'percent') {

            if ($company_id != '') {

                $max_disc = $db->get_row("SELECT * FROM " . $db->prefix . "companies WHERE (id = '" . $company_id . "' OR aph_id = '" . $company_id . "')");

                if ($max_disc['max_discount'] != '') {

                    if ($max_disc['max_discount'] < $promoDetails['discount_value']) {

                        $promoDetails['discount_value'] = $max_disc['max_discount'];

                    }

                }

            }

            $discount_amount = ($booking_price / 100) * $promoDetails['discount_value'];

            return $discount_amount;

        }

        if ($promoDetails['discount_type'] == 'gdp') {

            return $promoDetails['discount_value'];

        }

    }



    function varifyPromoCode($promoCode)

    {

        global $db;

        // if(!$_COOKIE['traffic']){

        // $discount_for = 'Og';

        // }

        // else{

        //$discount_for = $_COOKIE['traffic'];

        $Discount_filter = '';

        if ($_COOKIE['traffic'] == 'AF') {

            $discount_for = $_COOKIE['traffic'];

            $Discount_filter = "AND discount_for = '" . $discount_for . "'";

        }



        // }

        //$discount_for = 'FB';

        if ($promoCode != '') {

            $promoDetails = $db->get_row("SELECT * FROM " . $db->prefix . "discount 

                            WHERE promo = '" . $promoCode . "' AND status = 'Yes'

													

                            AND start_date <= '" . date("Y-m-d") . "' 

                            AND end_date >= '" . date("Y-m-d") . "'");

            if ($promoDetails != "") {

                if ($promoDetails['discount_campaign'] == 'General') {

                    $promofor = $db->get_row("SELECT * FROM " . $db->prefix . "discount 

                            WHERE promo = '" . $promoCode . "' AND status = 'Yes'

							$Discount_filter						

                            AND start_date <= '" . date("Y-m-d") . "' 

                            AND end_date >= '" . date("Y-m-d") . "'");

                    if ($promofor != "") {

                        return "Verify";

                    } else {

                        return "Discount Code is not Available through Refferal Sites";

                    }

                } else {

                    return "Not Verified";

                }

            } else {

                return "Discount Code Expired";

            }

        }

    }



    function getModuleSettings()

    {

        global $db;

        $_settings = array();

        $settings = $db->select("SELECT * FROM " . $db->prefix . "modules_setting");

        foreach ($settings as $setting) {

            $_settings[$setting['name']] = $setting['value'];

        }

        return $_settings;

    }



    function getSiteSettings()

    {

        global $db;

        $_settings = array();

        $settings = $db->select("SELECT * FROM " . $db->prefix . "setting");

        foreach ($settings as $setting) {

            $_settings[$setting['name']] = $setting['value'];

        }

        return $_settings;

    }



    function getHotelBookingPrice($hid, $rid, $rtid, $checkindate)

    {

        global $db;

        $checkindate = strtotime($checkindate);

        $year = date('Y', $checkindate);

        $month = date('n', $checkindate);

        $day = date('j', $checkindate);

        $room_price = $db->get_row("SELECT IFNULL(day_" . $day . ",0) AS price FROM " . $db->prefix . "hotel_pricebrand WHERE hotel_id ='" . $hid . "' AND month = '" . $month . "' AND year = '" . $year . "' AND room_type_id = '" . $rtid . "'");

        $_price = $room_price['price'];

        $roomDetails = $db->get_hotels_room($rid);

        $_defaul_price = $roomDetails['price_per_day'];

        if ($_price > 0) {

            return $_price;

        }

        return $_defaul_price;

    }



    function getloungesBookingPrice($hid, $aladults, $alchildren)

    {

        global $db;

        $lounge_price = $db->get_row("SELECT IF (IFNULL($aladults,0) > 0, lg.price_per_person*$aladults,0) AS price_per_person,

		IF (IFNULL($alchildren,0) > 0, lg.price_per_child*$alchildren,0) AS price_per_child FROM " . $db->prefix . "lounges AS lg where lg.id = '" . $hid . "'");

        $_price = $lounge_price['price_per_person'] + $lounge_price['price_per_child'];

        return $_price;

    }



    function getHotelParkingBookingPrice($hid, $rid, $rtid, $checkindate)

    {

        global $db;

        $checkindate = strtotime($checkindate);

        $year = date('Y', $checkindate);

        $month = date('n', $checkindate);

        $day = date('j', $checkindate);

        $room_price = $db->get_row("SELECT IFNULL(day_" . $day . ",0) AS price FROM " . $db->prefix . "hotel_parking_priceplan WHERE hotel_id ='" . $hid . "' AND month = '" . $month . "' AND year = '" . $year . "' AND room_type_id = '" . $rtid . "'");

        $_price = $room_price['price'];

        $roomDetails = $db->get_hotel_parking_room($rid);

        $_defaul_price = $roomDetails['default_price'];

        if ($_price > 0) {

            return $_price;

        }

        return $_defaul_price;

    }



    function check_extra($cid, $pid, $amount)

    {

        global $db;

        $amount_total = $db->get_row("SELECT extra from " . $db->prefix . "airport_price_plan where id = '" . $pid . "' and company_id = '" . $cid . "'");

        $amount_total = ($amount_total['extra'] * 1) + ($amount * 1);

        $amount_total = number_format($amount_total, 2, '.', '');

        return $amount_total;

    }



    function extra_amount($cid, $pid = '')

    {

        global $db;

        $extra = $db->get_row("SELECT extra from " . $db->prefix . "airport_price_plan where id = '" . $pid . "' and company_id = '" . $cid . "'");

        $extra = ($extra['extra'] * 1);

        $extra = number_format($extra, 2, '.', '');

        return $extra;

    }



    function APBookingPrice($cid, $aid, $no_of_days, $checkindate)

    {

        global $db;

        $checkindate = strtotime($checkindate);

        $year = date('Y', $checkindate);

        $month = date('n', $checkindate);

        $day = date('j', $checkindate);

        //$no_of_days = $no_of_days+1;

        if ($no_of_days > 30) {

            $total_days = '30';

        } else {

            $total_days = $no_of_days;

        }



        if (is_numeric($cid)) {

            $companies = $db->select("SELECT fc.id as companyID,

			fapp.id, fasb.brand, fapb.after_30_days, IF( fapb.day_$total_days >0, fapb.day_$total_days, fc.parking_per_day_price * $total_days) AS price FROM " . $db->prefix . "companies as fc

			join " . $db->prefix . "airport_price_plan as fapp on fc.id = fapp.company_id

			join " . $db->prefix . "airport_select_brand as fasb on fapp.id = fasb.plan_id and fasb.day_no = 'day_" . $day . "'

			join " . $db->prefix . "airport_price_brands as fapb on fapb.companyId = fc.id and fapb.brand = fasb.brand

			WHERE is_active = 'Yes' and fc.id = $cid and fc.airport_id = '" . $aid . "' and fapp.month = '" . $month . "' and fapp.year = '" . $year . "'");

            if ($companies !== false) {

                foreach ($companies as $company) {

                    //$price = number_format($company['price'], 2, '.', '');

                    if ($no_of_days > 30) {

                        $after30Days = $company['after_30_days'];

                        $booking_price = number_format($company['price'], 2, '.', '');

                        $booking_price = $booking_price + $after30Days * ($no_of_days - 30);

                        $booking_price = number_format($booking_price, 2, '.', '');

                    } else {

                        $booking_price = number_format($company['price'], 2, '.', '');

                    }

                }

            }

        } else {

            $booking_price = 0.00;

        }





        return $booking_price;

    }



    //function set_payment_array($orderID,$card,$card_brand,$expiry,$secure,$amount,$desc,$name,$email,$phone,$address,$town,$postal) {

    function set_payment_array($orderID, $card, $expiry, $secure, $amount, $desc, $name, $email, $phone, $address, $town, $postal)

    {

        $post_values = array(

            "PSPID" => "epdq3026850",

            "ORDERID" => $orderID,

            "USERID" => "cpayments",

            /*"PSWD"        	=> "Flypark@co.uk01", BKf]+*H#I4#TRS@@*/

            "PSWD" => "BKf]+*H#I4#TRS@@",

            "OPERATION" => "SAL",//Authorised. Put "SAL" for direct sale

            "PM" => "CreditCard",

            /*"BRAND"			=> "VISA",

            /* "BRAND"			=> $card_brand,*/



            "ECI" => "7",//- E-commerce with SSL/TLS encryption

            "CARDNO" => $card,

            "ED" => $expiry,

            "CVC" => $secure,

            "AMOUNT" => $amount,//Amount to be paid MULTIPLIED BY 100, as the format of the amount must not contain any decimals or other separators.

            "CURRENCY" => "GBP",

            "COM" => $desc,//Order description

            "CN" => $name,

            "EMAIL" => $email,

            "OWNERTELNO" => $phone,

            "OWNERADDRESS" => $address,

            "OWNERTOWN" => $town,

            "OWNERZIP" => $postal

        );

        $post_string = "";

        foreach ($post_values as $key => $value) {

            $post_string .= "$key=" . urlencode($value) . "&";

        }

        $post_string = rtrim($post_string, "& ");



        return $post_string;



    }



    function payment_process($post_url, $post_string)

    {



        $request = curl_init($post_url); // initiate curl object

        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response

        curl_setopt($request, CURLOPT_HTTPHEADER, ["application/x-www-form-urlencoded"]); //This is required for Barclaycard

        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)

        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data

        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.

        $post_response = curl_exec($request); // execute curl post and store results in $post_response



        curl_close($request); // close curl object



        //$post_response response is in xml format. Here we convert it to a object

        $simplexml_response = simplexml_load_string($post_response);



        $simplexml_response_array = (array)$simplexml_response;//Convert object to array because it is easier to manager for response object name start with @

        $respon = $simplexml_response_array['@attributes'];



        return $respon;

    }





    function getBookingByRefferenceNumber($reffNo)

    {

        global $db;

        $bookingDetails = false;

        $reffParts = explode('-', $reffNo);

        switch ($reffParts[0]) {

            case 'AP':

                $bookingDetails['type'] = 'airport_parking';

                $bookingDetails['data'] = $db->get_row("SELECT * FROM " . $db->prefix . "booking WHERE referenceNo = '" . $reffNo . "'");

                break;



            case 'AH':

                $bookingDetails['type'] = 'airport_hotel';

                $bookingDetails['data'] = $db->get_row("SELECT * FROM " . $db->prefix . "hotel_bookings WHERE referenceNo = '" . $reffNo . "'");

                break;



            case 'HP':

                $bookingDetails['type'] = 'hotel_parking';

                $bookingDetails['data'] = $db->get_row("SELECT * FROM " . $db->prefix . "hotel_parking_booking WHERE referenceNo = '" . $reffNo . "'");

                break;



            case 'AL':

                $bookingDetails['type'] = 'airport_lounge';

                $bookingDetails['data'] = $db->get_row("SELECT * FROM " . $db->prefix . "lounges_bookings WHERE referenceNo = '" . $reffNo . "'");

                break;

        }



        return $bookingDetails;

    }



    function getBookingByTokenNumber($tokenNo)

    {

        global $db;

        $bookingDetails = false;

        $_data = $db->get_row("SELECT * FROM " . $db->prefix . "booking WHERE token = '" . $tokenNo . "'");

        if (isset($_data['id']) && $_data['id'] > 0) {

            $bookingDetails['type'] = 'airport_parking';

            $bookingDetails['data'] = $_data;

            return $bookingDetails;

        }



        $_data = $db->get_row("SELECT * FROM " . $db->prefix . "hotel_bookings WHERE token = '" . $tokenNo . "'");

        if (isset($_data['id']) && $_data['id'] > 0) {

            $bookingDetails['type'] = 'airport_hotel';

            $bookingDetails['data'] = $_data;

            return $bookingDetails;

        }



        $_data = $db->get_row("SELECT * FROM " . $db->prefix . "hotel_parking_booking WHERE token = '" . $tokenNo . "'");

        if (isset($_data['id']) && $_data['id'] > 0) {

            $bookingDetails['type'] = 'hotel_parking';

            $bookingDetails['data'] = $_data;

            return $bookingDetails;

        }



        $_data = $db->get_row("SELECT * FROM " . $db->prefix . "lounges_bookings WHERE token = '" . $tokenNo . "'");

        if (isset($_data['id']) && $_data['id'] > 0) {

            $bookingDetails['type'] = 'airport_lounge';

            $bookingDetails['data'] = $_data;

            return $bookingDetails;

        }

        return $bookingDetails;

    }



    function getBookingByID($id)

    {

        global $db;

        $bookingDetails = false;

        $_data = $db->get_row("select b.id as booking_id,b.email,b.companyId,b.referenceNo,b.departDate,b.returnDate,b.no_of_days,b.title,b.first_name,b.last_name,b.payment_status,

		b.total_amount,b.phone_number,b.cancelfee,b.payment_method,b.phone_number,b.deptFlight,b.returnFlight,b.deprTerminal,b.airportID, v.make, v.model,v.color, v.registration, c.arival, c.return_proc,c.terms

		from " . $db->prefix . "booking as b join " . $db->prefix . "vehicle as v on v.bookingId = b.id 

		join " . $db->prefix . "companies as c on c.id = b.companyId where b.id='$id'");

        if (isset($_data['id']) && $_data['id'] > 0) {

            $bookingDetails['type'] = 'airport_parking';

            $bookingDetails['data'] = $_data;

            return $bookingDetails;

        }



        $_data = $db->get_row("select b.id as booking_id,b.email,b.companyId,b.referenceNo,b.departDate,b.returnDate,b.no_of_days,b.title,b.first_name,b.last_name,b.payment_status,

		b.total_amount,b.phone_number,b.cancelfee,b.payment_method,b.phone_number,b.deptFlight,b.returnFlight,b.deprTerminal,b.airportID, c.arival, c.return_proc,c.terms

		from " . $db->prefix . "booking as b join " . $db->prefix . "hotels as c on c.id = b.companyId where b.id='$id'");

        if (isset($_data['id']) && $_data['id'] > 0) {

            $bookingDetails['type'] = 'airport_hotel';

            $bookingDetails['data'] = $_data;

            return $bookingDetails;

        }



        $_data = $db->get_row("select b.id as booking_id,b.email,b.companyId,b.referenceNo,b.departDate,b.returnDate,b.no_of_days,b.title,b.first_name,b.last_name,b.payment_status,

		b.total_amount,b.phone_number,b.cancelfee,b.payment_method,b.phone_number,b.deptFlight,b.returnFlight,b.deprTerminal,b.airportID, c.arival, c.return_proc,c.terms

		from " . $db->prefix . "booking as b join " . $db->prefix . "hotel_parking as c on c.id = b.companyId where b.id='$id'");

        if (isset($_data['id']) && $_data['id'] > 0) {

            $bookingDetails['type'] = 'hotel_parking';

            $bookingDetails['data'] = $_data;

            return $bookingDetails;

        }



        return $bookingDetails;

    }



    //Get theme option

    function get_email($value)

    {

        global $db;

        $getemail = $db->get_row("select * from " . $db->prefix . "email_templates where title='$value'");

        if ($getemail) {

            $get_option['subject'] = stripslashes($getemail['subject']);

            $get_option['descriptions'] = stripslashes($getemail['description']);

            return $get_option;

        }

    }



    function newBookingNotification($token = '')

    {

        global $db;

        if ($token != '') {

            $_bookingDetails = $db->get_row("SELECT * FROM " . $db->prefix . "booking WHERE id = '" . $token . "'");

            sendCustomerNotification($_bookingDetails);

            sendAdminNotification($_bookingDetails);

            sendContractorNotification($_bookingDetails);

        }

    }



    function create_csv($type, $token, $status, $p_type = '')

    {

        global $db;

        if ($type == '') {

            $passenger = "";

            if ($p_type == "Park and Ride") {

                $passenger = "b.passenger AS Passengers,";

            }

            $results = $db->select("select 

					c.company_code AS ProductCode,

					b.referenceNo AS Refno,

					ap.airport_name AS Airport,

					DATE_FORMAT(b.createdate, '%Y-%m-%d') AS BookingDate,

					TIME_FORMAT(b.createdate, '%H:%i:%s') AS BookingTime, 

					DATE_FORMAT(b.departDate, '%Y-%m-%d') AS DepartureDate,

					TIME_FORMAT(b.departDate, '%H:%i:%s') AS DepartureTime, 

					DATE_FORMAT(b.returnDate, '%Y-%m-%d') AS ReturnDate,

					TIME_FORMAT(b.returnDate, '%H:%i:%s') AS ReturnTime, 

					IF(b.deprTerminal > 0, tr.terminal_name, 'TBA') As DepartureTerminal,

					IF(b.returnTerminal > 0, ts.terminal_name, 'TBA') As ReturnTerminal,

					b.no_of_days AS TotalDays,

					" . $passenger . "

					b.title AS Title,

					b.first_name AS FirstName,

					b.last_name AS LastName,

					b.phone_number AS Telephone,

					b.booking_amount AS BookingPrice,

					(b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As CompanyShare,

				    (c.share_percentage/100*(b.booking_amount)) As AgentShare,

					b.deptFlight AS DeptFlight,

					b.returnFlight AS ReturnFlight,

					v.make AS CarMake, 

					v.model AS CarModel,

					v.color AS CarColor,

					v.registration AS CarRegistration

			from " . $db->prefix . "booking as b join " . $db->prefix . "vehicle as v on v.bookingId = b.id 

			join fpp_companies as c on c.id = b.companyId

			join " . $db->prefix . "airports as ap on ap.id = b.airportID 

			left join " . $db->prefix . "terminals as tr on tr.id = b.deprTerminal

			left join " . $db->prefix . "terminals as ts on ts.id = b.returnTerminal

			where b.id='$token'");

        }

        if ($results > 0) {

            $datenow = date("dmYhms");

            $name = "FPP_$datenow.csv";

            $filepath = csvpath . $name;

            $outstream = fopen($filepath, "w");

            if ($status != "") {

                $results[0]['BookingStatus'] = 'BookingStatus';

            }

            fputcsv($outstream, array_keys($results[0]));



            foreach ($results as $result) {

                if ($status != "") {

                    $result['BookingStatus'] = $status;

                }

                fputcsv($outstream, $result);

            }



            // fclose($outstream);

            rewind($outstream);

            fclose($outstream);

        }

        return $filepath;

    }



    function smtpmailers($to, $touser, $from, $from_name, $subject, $body, $files = '', $mode = '')

    {

        //$path = $files."airport_parking.csv";

        global $error;

        global $flag;

        $mail = new PHPMailer();



        $mail->IsSMTP();

        $mail->SMTPDebug = 0;

        $mail->SMTPAuth = TRUE;

        $mail->SMTPSecure = "tls";

        $mail->Port = 587;

        $mail->Username = 'fppreservations@flyparkplus.co.uk';

        $mail->Password = '5OB%SAEy@c6k23';



        // $host = "ssl://smtp.gmail.com";

        $mail->Host = "smtp.gmail.com";

        $mail->Mailer = "smtp";

        $mail->SetFrom('bookings@flyparkplus.co.uk', $from_name);

        $mail->Subject = $subject;

        //$mail->Body = $body;



        $mail->WordWrap = 80;

        $mail->MsgHTML($body);

        $mail->IsHTML(true);

        $too = explode(",", $to);

        foreach ($too as $to) {

            $mail->AddAddress($to, $touser);

        }



        if ($files != "") {

            $mail->AddAttachment($files);

        }

        if (!$mail->Send()) {

            $error = 'Mail error: ' . $mail->ErrorInfo;

            $flag = 0;

            $mail->ClearAddresses();

            if ($files != "") {

                if ($mode == '') {

                    unlink($files);

                }

            }

            return $flag;



        } else {

            $mail->ClearAddresses();

            $error = 'Message sent!';

            $flag = 1;

            if ($files != "") {

                if ($mode == '') {

                    unlink($files);

                }

            }

            return $flag;



        }

    }



    function email_signUP($customer_id, $password)

    {

        global $db;

        $user = $db->get_row("SELECT * FROM " . $db->prefix . "customer WHERE  id = '" . $customer_id . "'");

        $user_email = $user['email'];

        $user_name = $user['first_name'] . " " . $user['last_name'];

        $user_password = $password;



        $email = $db->get_row("SELECT * FROM " . $db->prefix . "email_templates WHERE  title = 'Signup' && status = 'Yes'");

        $subject = $email['subject'];

        $message = str_replace("[login]", $user_email, $email['description']);

        $message = str_replace("[password]", $user_password, $message);

        $message = str_replace("[username]", $user_name, $message);



        $from = "noreply@flyparkplus.co.uk";

        $from_name = "Total Travel Solutions";



        $sent_email = smtpmailers($user_email, $user_name, $from, $from_name, $subject, $message);





    }



    function sendCustomerNotification($booking)

    {

        $macros = array("[airport]",

            "[conpany_name]",

            "[username]",

            "[email]",

            "[phone_number]",

            "[address]",

            "[address2]",

            "[town]",

            "[postal_code]",

            "[ref]",

            "[startdate]",

            "[deprTerminal]",

            "[deptFlight]",

            "[enddate]",

            "[price]",

            "[bookingfee]",

            "[smsfee]",

            "[no_of_days]",

            "[token]",

            "[discount_amount]",

            "[cancelfee]",

            "[total_amount]");

    }



    function notifications($token = '', $status, $email = '', $id = '', $pass = '', $type = '')

    {

        global $db;

        if ($_SERVER['HTTP_HOST'] == 'localhost') {

            return false;

        }

        // Always set content-type when sending HTML email

        //$headers = "MIME-Version: 1.0" . "\r\n";

        //$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";

        //$headers .= "X-Mailer: PHP \r\n";



        $from = "noreply@flyparkplus.co.uk";

        $from_name = "Total Travel Solutions";



        // More headers

        //$headers .= 'From: JETSEEKER <noreply@flyparkplus.com>' . "\r\n";

        //getting booking result

        if ($type == '') {

            $users = $db->get_row("select b.id as booking_id,b.email,b.ext_ref,b.booked_type,b.companyId,b.referenceNo,DATE_FORMAT(b.departDate, '%D %b %Y at %H:%i') AS departDate, DATE_FORMAT(b.createdate, '%D %b %Y at %H:%i') AS createdate,DATE_FORMAT(b.returnDate, '%D %b %Y at %H:%i') AS returnDate,b.no_of_days,b.title,b.first_name,b.last_name,b.payment_status,b.token,c.parking_type,

	b.payment_method,b.total_amount,b.cancelfee,b.booking_amount,b.phone_number,b.deptFlight,b.returnFlight,b.deprTerminal,b.returnTerminal,b.airportID, v.make, v.model,v.color, v.registration, IF(c.name IS NULL , b.booked_type, c.name) As cname, c.arival, c.return_proc,c.terms,c.admin_id,c.company_email,c.company_code

    from " . $db->prefix . "booking as b left join " . $db->prefix . "vehicle as v on v.bookingId = b.id

	left join " . $db->prefix . "companies as c on c.id = b.companyId or c.aph_id = b.companyId where b.id='$token'");



            $t_query = "select * from " . $db->prefix . "booking_transaction as ts where ts.orderID = '" . $users['booking_id'] . "' order by id desc limit 1";

            $tr_query = $db->query($t_query);

            $tr_rows = $tr_query->num_rows;

            if ($tr_rows > 0) {

                $transactions = $tr_query->fetch_array();

                $payable = $transactions['payable'];

                $amount_type = $transactions['amount_type'];

            }

            //$files = "";

        }



        if ($type == 'hotels') {

            $users = $db->get_row("SELECT h.id,h.hotel_id,h.room_id,h.email,h.phone_number,h.room_type_id,h.referenceNo,h.check_in,h.createdate,h.no_of_days,h.first_name,h.last_name,h.cancelfee,h.payment_status,h.token,

                    h.booking_status,h.total_amount,h.payment_method,h.booking_amount,h.deptFlight,h.returnFlight, h.deprTerminal,h.returnTerminal,h.airportID, c.terms, c.hotel_name

    from " . $db->prefix . "hotel_bookings as h

	join " . $db->prefix . "hotels as c on c.id = h.hotel_id where h.id='$token'");

        }



        if ($type == 'hotelsparking') {

            $users = $db->get_row("SELECT h.id,h.hotel_id,h.room_id,h.email,h.phone_number,h.room_type_id,h.referenceNo,h.check_in,h.check_until,h.createdate,h.no_of_days,h.first_name,h.last_name,h.cancelfee,h.payment_status,h.token,

                    h.booking_status,h.total_amount,h.payment_method,h.booking_amount,h.deptFlight,h.returnFlight,h.deprTerminal,h.returnTerminal,h.airportID, v.make, v.model,v.color, v.registration, c.departure, c.return,c.terms,c.hotel_name

    from " . $db->prefix . "hotel_parking_booking as h  join " . $db->prefix . "hpvehicle as v on v.bookingId = h.id 

	join " . $db->prefix . "hotel_parking as c on c.id = h.hotel_id where h.id='$token'");

        }



        if ($type == 'lounges') {

            $users = $db->get_row("SELECT l.id as booking_id, l.referenceNo_ext, l.adults, l.children, l.lounge_id,l.email,l.phone_number,l.referenceNo,l.check_in,l.lounge_name,l.check_in_time,l.createdate,l.no_of_days,l.first_name,l.last_name,l.cancelfee,l.payment_status,l.token,

                    l.booking_status,l.total_amount,l.payment_method,l.booking_amount,l.deptFlight,l.returnFlight,l.deprTerminal,l.returnTerminal,l.airportID

    from " . $db->prefix . "lounges_bookings as l where l.id='$token'");



            $lname = $users['lounge_name'];

            $adults = $users['adults.'];

            $childs = $users['children.'];



            $lounge_info = "$lname for $adults adults and $childs children";

        }



        $user = stripslashes($users['title']) . " " . stripslashes($users['first_name']) . " " . stripslashes($users['last_name']);

        if (!empty($users['arival']) OR !empty($users['return_proc'])) {

            $guidence = "<h3 style='color:#337ab7'><strong >Arrival</strong></h3><p>" . $users['arival'] . "</p><h3 style='color:#337ab7'><strong>Return </strong></h3><p>" . $users['return_proc'] . "</p></br>";

        } else {

            $guidence = "<h3 style='color:#337ab7'><strong >Arrival</strong></h3><p>Will be guided at the terminal</p><h3 style='color:#337ab7'><strong>Return </strong></h3><p>Will be guided at the terminal</p></br>";

        }//$vehicle = "Reg:".stripslashes($users['registration'])."<br> Make:".stripslashes($users['make'])."<br> Model:".stripslashes($users['model'])."<br> Color:".stripslashes($users['color']);

        $airport = $db->get_row("SELECT airport_name FROM " . $db->prefix . "airports where id = '" . $users['airportID'] . "'");

        $terminals = $db->get_row("select * from " . $db->prefix . "terminals where id = '" . $users['deprTerminal'] . "'");

        $terminalz = $db->get_row("select * from " . $db->prefix . "terminals where id = '" . $users['returnTerminal'] . "'");

        if ($terminals == "") {

            $terminals = 'TBA';

        } else {

            $terminals = $terminals['terminal_name'];

        }

        if ($terminalz == "") {

            $terminalz = 'TBA';

        } else {

            $terminalz = $terminalz['terminal_name'];

        }

        $explodes = explode("FP", $users['company_code']);

        $cname = $explodes[1];

        $cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '" . $cname . "'");

        $cname = isset($cname['name']) ? $cname['name'] : $users['booked_type'];

        if (preg_match('/aph/', strtolower($cname))) {

            $aph = 'APH Ref:';

            $aph_ref = $users['ext_ref'];

        } else {

            $aph = '';

            $aph_ref = '';

        }



        if (empty($email)) {

            $email = $users['email'];

        }

        if ($email == 'admin') {

            $admin = $db->get_admin(1);

            $email = $admin['email'];

            //$user  = stripslashes($admin['first_name'])." ".stripslashes($admin['last_name']);

        }

        if ($email == 'company') {

            $admin = $db->get_admin($users['admin_id']);

            $email = $users['company_email'];

            $company = stripslashes($admin['first_name']) . " " . stripslashes($admin['last_name']);

            $stat = strtolower($status);



            if (preg_match('/cancel/', $stat)) {

                $stat = 'Cancel';

                $files = create_csv("", $token, $stat, $users['parking_type']);

            } else if (preg_match('/update/', $stat)) {

                $stat = 'Amend';

                $files = create_csv("", $token, $stat, $users['parking_type']);

            } else if (!preg_match('/Cancel/', $stat) OR !preg_match('/update/', $stat)) {

                $stat = 'Next';

                $files = create_csv("", $token, $stat, $users['parking_type']);

            }

        }



        //can add many status here

        //status means title of the email template



        $status = isset($status) ? $status : '';



        if ($status == 'Subscription') {

            $sb_name = $db->get_row("select * from " . $db->prefix . "subscribers where id = '" . $token . "'");

            $user = $sb_name['name'];

        }

        if ($status == 'client Lounges booking' or $status == 'Add Lounges Booking') {

            $files = pdfpath . $users['referenceNo_ext'] . ".pdf";

        }





        if (!empty($status)) {

            $data = get_email($status);

            $subject = $data['subject'] . ' Ref # ' . $users['referenceNo'];

            $message = str_replace("[username]", $user, $data['descriptions']);

            $message = str_replace("[login]", $email, $message);

            $message = str_replace("[password]", $pass, $message);

            $message = str_replace("[payment_status]", $users['payment_status'], $message);

            $message = str_replace("[token]", $users['token'], $message);

            $message = str_replace("[price]", $users['total_amount'], $message);

            $message = str_replace("[payment_gatway]", $users['payment_method'], $message);

            $message = str_replace("[ref]", $users['referenceNo'], $message);

            $message = str_replace("[aph]", $aph, $message);

            $message = str_replace("[aph_ref]", $aph_ref, $message);

            $message = str_replace("[email]", $email, $message);

            $message = str_replace("[cemail]", $users['email'], $message);

            $message = str_replace("[c_code]", $users['company_code'], $message);

            $message = str_replace("[c_parent]", $cname, $message);

            $message = str_replace("[telephone]", $users['phone_number'], $message);

            $message = str_replace("[airport]", $airport['airport_name'], $message);

            $message = str_replace("[carpark]", $users['cname'], $message);

            $message = str_replace("[ptype]", $users['booked_type'], $message);

            $message = str_replace("[terminal]", $terminals, $message);

            $message = str_replace("[rterminal]", $terminalz, $message);

            $message = str_replace("[days]", $users['no_of_days'], $message);

            $message = str_replace("[start_date]", $users['departDate'], $message);

            $message = str_replace("[check_in]", $users['check_in'], $message);

            $message = str_replace("[end_date]", $users['returnDate'], $message);

            $message = str_replace("[bprice]", $users['booking_amount'], $message);

            $message = str_replace("[guidence]", $guidence, $message);

            $message = str_replace("[terms]", $users['terms'], $message);

            $message = str_replace("[reg]", $users['registration'], $message);

            $message = str_replace("[make]", $users['make'], $message);

            $message = str_replace("[model]", $users['model'], $message);

            $message = str_replace("[booktime]", $users['createdate'], $message);

            $message = str_replace("[color]", $users['color'], $message);

            $message = str_replace("[r_flight_no]", $users['returnFlight'], $message);

            $message = str_replace("[d_flight_no]", $users['deptFlight'], $message);

            $message = str_replace("[refund]", $users['booking_amount'], $message);

            $message = str_replace("[company]", $company, $message);

            $message = str_replace("[lounge]", $users['lounge_name'], $message);

            $message = str_replace("[lounge_info]", $lounge_info, $message);

            $message = str_replace("[in_ref]", base64_encode($users['referenceNo']), $message);

        }



        if ($_SERVER['HTTP_HOST'] != 'localhost') {

            //$sent_email = mail($email,$subject, $message,$headers);

            $sent_email = smtpmailers($email, $user, $from, $from_name, $subject, $message, $files);

        }

        if ($sent_email == '1') {

            $msg = base64_encode('Email Succussfully send..!!');

        } else {

            $msg = base64_encode('Email Sending fail try again..!!');

        }



        $return_array = array('token' => $token, 'email_not' => $sent_email, 'msg' => $msg, 'userdata' => $data, 'files' => $files);

        return $return_array;

    }



    function checkUserAuth()

    {

        if ($_SESSION['login_id'] > 0 && $_SESSION['is_login'] == 'Yes') {

            return true;

        }

        header("location:customer_login.php");

    }



    function showFrmMessage($msg)

    {

        $html = '';

        $msg = base64_decode($msg);

        list($type, $message) = explode(":", $msg);

        if (strtolower($type) == 'error') {

            $html .= '<div class="alert alert-danger" role="alert">';

            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';

            $html .= '<span aria-hidden="true">&times;</span>';

            $html .= '</button>';

            $html .= '<p>' . $message . '</p>';

            $html .= '</div>';

        }

        if (strtolower($type) == 'success') {

            $html .= '<div class="alert alert-success" role="alert">';

            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';

            $html .= '<span aria-hidden="true">&times;</span>';

            $html .= '</button>';

            $html .= '<p>' . $message . '</p>';

            $html .= '</div>';

        }



        return $html;

    }



    function getHoursDifference($time)

    {

        $selectedTime = strtotime($time);

        $currentTime = strtotime(date("H:i"));

        $diff = round(abs($selectedTime - $currentTime) / 3600, 2);

        return $diff;

    }



    function getCompanyBookedSlots($_dropdate, $_dropoftime, $_pickdate, $_pickuptime, $_companyID)

    {

        global $db;

        $_from_date = date("Y-m-d", strtotime($_dropdate)) . ' ' . date("H:i:s", strtotime($_dropoftime));

        $_to_date = date("Y-m-d", strtotime($_pickdate)) . ' ' . date("H:i:s", strtotime($_pickuptime));

        $_bookings = $db->get_row("SELECT COUNT(id) as booked_slots FROM " . $db->prefix . "booking as b WHERE companyId = '" . $_companyID . "' AND departDate BETWEEN '" . $_from_date . "' AND '" . $_to_date . "' AND removed = 'No' AND parent_id = 0 AND booking_status = 'Completed'");

        $_booked_slots = $_bookings['booked_slots'];

        $c_bookings = $db->get_row("SELECT COUNT(id) as booked_slots FROM " . $db->prefix . "booking as b WHERE companyId = '" . $_companyID . "' AND departDate BETWEEN '" . $_from_date . "' AND '" . $_to_date . "' AND removed = 'No' AND parent_id > 0 AND booking_status = 'Cancelled'");

        return $_booked_slots - $c_bookings['booked_slots'];

    }



    function pdf_generator($url, $saveto)

    {

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

        $raw = curl_exec($ch);

        curl_close($ch);

        if (file_exists($saveto)) {

            unlink($saveto);

        }

        $fp = fopen($saveto, 'x');

        fwrite($fp, $raw);

        fclose($fp);

    }



    function object_to_array($data)

    {

        if (is_array($data) || is_object($data)) {

            $result = array();

            foreach ($data as $key => $value) {

                $result[$key] = object_to_array($value);

            }

            return $result;

        }

        return $data;

    }

}

?>

