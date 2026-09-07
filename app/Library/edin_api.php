<?php

class edin_api
{

    function edinburghairport_create_booking($search, $product, $firstName, $lastName, $carReg, $tpRef)
    {
        $postfields = array('search' => $search, 'product' => $product, 'firstName' => $firstName, 'lastName' => $lastName, 'carReg' => $carReg, 'tpRef' => $tpRef);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, "es81qrRUH2TLCXaz" . ":" . "");
        curl_setopt($ch, CURLOPT_URL, 'https://api.edinburghairport.com/v1/bookings');
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

    function edinburghairport_companies_call($entry_date, $exit_date)
    {
        $postfields = array('entryTime' => $entry_date, 'exitTime' => $exit_date);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, "es81qrRUH2TLCXaz" . ":" . "");
        curl_setopt($ch, CURLOPT_URL, 'https://api.edinburghairport.com/v1/search/');
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


    function get_edin_companies($result)
    {
        global $db;
        $edin_companies = array();
        if ($result) {
            foreach ($result['products'] as $row) {
                $price = $row['price'] . '0';
                $edin_price = number_format($price, 0, ',', '.');;
                $edin_company = $db->get_row("SELECT * FROM " . $db->prefix . "companies where edin_id = '" . $row['code'] . "' && Is_active= 'Yes'");
                if ($edin_company) {
                    $edin_company['edin_search'] = $result['id'];
                    $edin_company['edin_code'] = $row['code'];
                    $edin_company['price'] = $edin_price;
                    $edin_company['id'] = $edin_company['id'];
                    $edin_company['companyID'] = $edin_company['id'];
                    $edin_company['edin_active'] = 1;
                    $edin_companies[] = $edin_company;
                }
            }
        }
        return $edin_companies;
    }

    function get_edin_data($dropdate, $pickdate, $dropoftime, $pickuptime)
    {
        $edinArrivalDate = date('Y-m-d', strtotime($dropdate));
        $edinDepartDate = date('Y-m-d', strtotime($pickdate));
        $edinArrivalTime = date("H:i", strtotime($dropoftime));
        $edinDepartTime = date("H:i", strtotime($pickuptime));
        $edin_dropdate = $edinArrivalDate . " " . $edinArrivalTime;
        $edin_pickdate = $edinDepartDate . " " . $edinDepartTime;


        $edin_result = edinburghairport_companies_call($edin_dropdate, $edin_pickdate);
        $edin_companies = get_edin_companies($edin_result);
        return $edin_companies;
    }

    function get_edin_company_price($dropdate, $pickdate, $dropoftime, $pickuptime, $edin_active)
    {
        $edinArrivalDate = date('Y-m-d', strtotime($dropdate));
        $edinDepartDate = date('Y-m-d', strtotime($pickdate));
        $edinArrivalTime = date("H:i", strtotime($dropoftime));
        $edinDepartTime = date("H:i", strtotime($pickuptime));
        $edin_dropdate = $edinArrivalDate . " " . $edinArrivalTime;
        $edin_pickdate = $edinDepartDate . " " . $edinDepartTime;
        $edin_result = edinburghairport_companies_call($edin_dropdate, $edin_pickdate);
        $edin_companies = get_edin_companies($edin_result);
        $company = Search_IN_ARRAY($edin_companies, 'edin_code', $edin_active);
        return $company;
    }

}

?>