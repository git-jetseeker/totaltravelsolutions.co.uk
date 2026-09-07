<?php

class ace_api
{
    function ACEBooking($airport_id, $from_date, $from_time, $to_date, $to_time)
    {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
        $from_time = date("H:i", strtotime($from_time));
        $to_time = date("H:i", strtotime($to_time));

        $url = "https://maple.use-fuse.com/api/package";
        $getString = "?user=flyparkplus&userkey=59e6322b3885b";
        $getString .= "&airport=" . $airport_id;
        $getString .= "&from=" . $from_date . "%20" . $from_time;
        $getString .= "&to=" . $to_date . "%20" . $to_time;
        $final = $url . $getString;
        $result = curl_call($final);
        $result = json_decode($result, true);
        //$result = Search_IN_ARRAY($result, 'status', 'Enabled');
        $data = ace_list($result);
        return $data;
    }

    function ACESingle($sku, $from_date, $from_time, $to_date, $to_time)
    {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
        $from_time = date("H:i", strtotime($from_time));
        $to_time = date("H:i", strtotime($to_time));

        $url = "https://maple.use-fuse.com/api/package";
        $getString = "?user=flyparkplus&userkey=59e6322b3885b";
        $getString .= "&sku=" . $sku;
        $getString .= "&from=" . $from_date . "%20" . $from_time;
        $getString .= "&to=" . $to_date . "%20" . $to_time;
        $final = $url . $getString;
        $result = curl_call($final);
        $result = json_decode($result, true);
        $data = $result['DATA'][0]['price'];
        return $data;
    }


    function ace_list($data)
    {
        $array = array();
        $nested = array();
        foreach ($data['DATA'] as $item) {
            if ($item['status'] == 'Enabled') {
                $nested['price'] = $item['price'];
                $nested['sku'] = $item['sku'];
                $nested['name'] = $item['name'];
                $nested['airport'] = $item['airport'];
                $array[] = array_flatten($nested);
            }
        }
        $array = json_decode(json_encode((array)$array), TRUE);
        return $array;
    }
}
?>