<?php

namespace App\Library;

use App\Models\modules_settings;
use App\Models\settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class aph_functions
{
    public $_mysetting = [];

    public $_setting = [];

    public $_aphdetailurl = [];

    public $_addextra = [];

    public $_addextraAPH = [];

    public $_aphkey = [];

    public function __construct()
    {

        $modules_settings = modules_settings::all();

        foreach ($modules_settings as $setting) {
            $this->_setting[$setting->name] = $setting->value;
        }
        $my_settings = settings::all();
        foreach ($my_settings as $my_setting) {
            $this->_mysetting[$my_setting->field_name] = $my_setting->field_value;

        }

        if ($this->_mysetting['holiday_extra_type'] != 'Inactive') {

            $this->_addextra = $this->_mysetting['holiday_extra_amount'];
        } else {
            $this->_addextra = 0;
        }
        if ($this->_mysetting['aph_api'] == 'Active_Live') {

            $this->_aphurl = $this->_mysetting['aph_live_url'];
            $this->_aphdetailurl = $this->_mysetting['aph_live_detail_url'];

        } else {
            $this->_aphurl = $this->_mysetting['aph_test_url'];
            $this->_aphdetailurl = $this->_mysetting['aph_test_detail_url'];
        }
        if ($this->_mysetting['aph_extra_type'] != 'Inactive') {

            $this->_addextraAPH = $this->_mysetting['aph_extra_amount'];
            $this->_aphkey = $this->_mysetting['aph_key'];
        } else {
            $this->_addextraAPH = 0;
            $this->_aphkey = $this->_mysetting['aph_key'];
        }
        if ($this->_mysetting['a2z_api'] == 'Active_Live') {

            $this->_a2zurl = $this->_mysetting['a2z_url_live'];

        } else {
            $this->_a2zurl = $this->_mysetting['a2z_url_test'];
        }
        if ($this->_mysetting['a2z_extra_type'] != 'Inactive') {
            $this->_a2zExtra = $this->_mysetting['a2z_extra_amount'];

        } else {
            $this->_a2zExtra = 0;
        }
        

    }

    public function a2zBookingOrder($data)
    {
        $url = $this->_a2zurl.'payment/';
        $response = $this->a2z_call($data, $url);
        return $response;
    }

    public function a2zListings($airport_code, $dropoff_date, $dropoff_time, $pickup_date, $pickup_time)
    {
        $initData = [
            'user' => $this->_mysetting['a2z_user'],
            'key' => $this->_mysetting['a2z_key'],
            'action' => 'airportparking',
            'quote' => [
                'airport' => $airport_code,
                'depdate' => date('Y-m-d', strtotime($dropoff_date)),
                'deptime' => $dropoff_time,
                'returndate' => date('Y-m-d', strtotime($pickup_date)),
                'returntime' => $pickup_time,
            ],
        ];
        
        $url = $this->_a2zurl.'parking/';
        $response = $this->a2z_call($initData, $url);
        if ($this->_mysetting['a2z_list_update'] == 'Active') {
            $allProdUrl = $this->_a2zurl.'products/';
            $allProdData = [
                'user' => $this->_mysetting['a2z_user'],
                'key' => $this->_mysetting['a2z_key'],
                'airport' => $airport_code,
                'action' => 'parkingproducts',
            ];
        
            $allProdDatas = $this->a2z_call($allProdData, $allProdUrl);
        }
        foreach ($response->products as $item) {

            // $companyShare = DB::table('companies')->where('company_code', $item->productsku)->first();
            // $companyShare = $companyShare->share_percentage;
            // if ($companyShare >= $this->_mysetting['a2z_extra_amount']) {
            //     $adjustedShare = $companyShare;
            // } else {
            //     $difference = $this->_mysetting['a2z_extra_amount'] - $companyShare;
            //     $adjustedShare = $difference;
            // }

            // if ($this->_mysetting['a2z_extra_type'] == 'Percentage') {
            //     $this->_addextra = number_format($adjustedShare / 100 * $item->quoteamount, 2);
            // } else {
            //     $this->_addextra = number_format($this->_mysetting['a2z_extra_amount']);
            // }
            
            $companyShare = DB::table('companies')->where('company_code', $item->productsku)->first();
            $companyShare = $companyShare->share_percentage;
            
            
             // IF airport is heathrow and share percentage is less than 25% then exclude that company
            
            if(($airport_code=="LHR")&&($companyShare<25)){

                continue;
            }

            $nested['price'] = number_format($item->quoteamount);
            // $nested['EA'] = $this->_addextra;
            $nested['sku'] = $item->productsku;
            $nested['companyID'] = $item->productsku;
            $nested['productname'] = $item->productname;

            $logo = explode('products/', $item->productlogo);
            $logo = str_replace('">', '', $logo['1']);
            $nested['logo'] = 'https://api.wecompareparking.com/assets/images/products/'.$logo;
            // $nested['productlogo'] = $item->productlogo;
            $nested['servicetype'] = $item->servicetype;
            $nested['nonflex'] = $item->nonflex;
            $nested['parkmark'] = $item->parkmark;
 
            if ($this->_mysetting['a2z_list_update'] == 'Active' && $allProdDatas && isset($allProdDatas->products)) {
                foreach ($allProdDatas->products as $product) {
                    if (isset($product->productsku) && $product->productsku == $item->productsku) {
                        // Found the product with matching SKU
                        $foundProductData = $product;

                        $companies = DB::table('companies')->where('company_code', $item->productsku)->first();
                        $airport = DB::table('airports')->where('iata_code', $airport_code)->first();

                        if (empty($companies)) {
                            $data['company_code'] = $foundProductData->productsku;
                            $data['name'] = $foundProductData->productname;
                            $data['opening_time'] = $foundProductData->operationstart;
                            $data['closing_time'] = $foundProductData->operationend;
                            $data['company_email'] = $foundProductData->bookingemail;

                            $htmlString = $foundProductData->productlogo;

                            if (preg_match('/<img.*?src=["\'](.*?)["\'].*?>/', $htmlString, $matches)) {
                                $srcAttribute = $matches[1];
                                $data['logo'] = $logo;
                            }
                            if ($foundProductData->servicetype == 1) {
                                $data['parking_type'] = 'Meet and Greet';
                                $data['editable'] = 'Yes';
                                $data['cancelable'] = 'Yes';
                            } else {
                                $data['parking_type'] = 'Park and Ride';
                            }
                            if ($foundProductData->nonflex == 0) {
                                $data['is_flex'] = '1';
                            } else {
                                $data['is_flex'] = '0';
                            }
                            $data['processtime'] = $foundProductData->bookhours;
                            if ($foundProductData->patrolling == 1) {
                                $special_features[] = 'PATROLLED';
                            }
                            if ($foundProductData->cctv == 1) {
                                $special_features[] = 'CCTV';
                            }
                            if ($foundProductData->keepkeys == 1) {
                                $special_features[] = 'KEEP YOUR KEYS';
                            }
                            if ($foundProductData->securitylight == 1) {
                                $special_features[] = 'SECURITY LIGHTING';
                            }
                            if ($foundProductData->securebarrier == 1) {
                                $special_features[] = 'SECURE BARRIER';
                            }
                            if ($foundProductData->disability == 1) {
                                $special_features[] = 'DISABILITY FRIENDLY';
                            }
                            if ($foundProductData->parkmark == 1) {
                                $special_features[] = 'PARK MARK CERTIFIED';
                            }
                            if ($foundProductData->approvedoperator == 1) {
                                $special_features[] = 'APPROVED OPERATOR';
                            }
                            if ($foundProductData->officialparking == 1) {
                                $special_features[] = 'OFFICIAL PARKING';
                            }

                            $data['special_features'] = $special_features ? implode(',', $special_features) : '';
                            $data['ShortDescription'] = $foundProductData->productdescription;
                            $data['overview'] = $foundProductData->productoverview;
                            $data['arival'] = $foundProductData->dropoffprocedure.'<br> <b>Directions</b> <br>'.$foundProductData->directions.'<br> <b>Important Info:</b> <br>'.$foundProductData->importantinfo.'<br><b>Airport Number: </b>'.$foundProductData->airportnumber;
                            $data['arivalfront'] = $foundProductData->dropoffprocedure.'<br> <b>Directions</b> <br>'.$foundProductData->directions.'<br> <b>Important Info:</b> <br>'.$foundProductData->importantinfo.'<br><b>Airport Number: </b>'.$foundProductData->airportnumber;
                            $data['importantinformation'] = $foundProductData->importantinfo;
                            $data['return_proc'] = $foundProductData->arrivalprocedure;
                            $data['returnfront'] = $foundProductData->arrivalprocedure;
                            $data['share_percentage'] = $foundProductData->commission;
                            $data['admin_id'] = 153;
                            $data['airport_id'] = $airport->id;
                            $data['is_active'] = 'Yes';
                            $data['terminal'] = '0';

                            $create = DB::table('companies')->insertGetId($data);
                            $nested['companyID'] = $create;
                            if ($create) {
                                $facilities = DB::table('facilities')->where('company_id', $create)->first();
                                if (empty($facilities)) {
                                    $facis = explode("\r\n", $foundProductData->productdescription);

                                    foreach ($facis as $fac) {

                                        $up_fac = DB::insert('insert into facilities (company_id,description,type) values (?, ?, ? )',
                                            [$create, $fac, 'company']);

                                    }
                                }
                            }
                        }

                    }
                }
            }

            $nested = json_decode(json_encode((array) $nested), true);
            $nested = $this->array_flatten($nested);
            $array[] = $this->array_flatten($nested);

        }

        return $array;

    }

    public function a2z_call($data, $url)
    {

        set_time_limit(120);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Cache-Control: no-cache']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        return json_decode($response);
    }

    public function AphBookingPrice($ArrivalDate, $DepartDate, $ArrivalTime, $DepartTime, $aph_company_id, $passenger, $product_code)
    {
        $xml = '<API_Request
    				System="APH"
    				Version="1.0"
    				Product="CarPark"
    				Customer="X"
    				Session="000000004"
    				RequestCode="3">
    				<Agent>
    				<ABTANumber>'.config('app.ABTANumber').'</ABTANumber>
    				<Password>'.config('app.Password').'</Password>
    				<Initials>'.config('app.Initials').'</Initials>
    				</Agent>
    				<Itinerary>
    				<ArrivalDate>'.$ArrivalDate.'</ArrivalDate>
    				<DepartDate>'.$DepartDate.'</DepartDate>
    				<ArrivalTime>'.$ArrivalTime.'</ArrivalTime>
    				<DepartTime>'.$DepartTime.'</DepartTime>
    				<CarParkCode>'.$aph_company_id.'</CarParkCode>
    				<ProductCode>'.$product_code.'</ProductCode>
    				<NumberOfPax>'.$passenger.'</NumberOfPax>
    				</Itinerary>
    				</API_Request>';
        $response = $this->aph_call($xml);
        $xml_aph = simplexml_load_string($response);
        $xml_aph = json_decode(json_encode((array) $xml_aph));

        //return 50;

        $booking_price = $xml_aph->Pricing->TotalPrice;

        return $booking_price;
    }

    public function AphBookingOrder($data)
    {

        $response = $this->aph_call($data);
        // 	dd($response );

        $xml_aph = simplexml_load_string($response);
        $array = [];
        foreach ($xml_aph as $item) {
            $array['BookingRef'] = $xml_aph->Booking->BookingRef;
            $array['ArrivalDate'] = $xml_aph->Itinerary->ArrivalDate;
            $array['DepartDate'] = $xml_aph->Itinerary->DepartDate;
            $array['ArrivalTime'] = $xml_aph->Itinerary->ArrivalTime;
            $array['DepartTime'] = $xml_aph->Itinerary->DepartTime;
            $array['no_of_days'] = $xml_aph->Itinerary->Duration;
            $array['CarParkCode'] = $xml_aph->Itinerary->CarParkCode;
            $array['NumberOfPax'] = $xml_aph->Itinerary->NumberOfPax;
            $array['ReturnFlight'] = $xml_aph->Itinerary->ReturnFlight;
            $array['DepTerminal'] = $xml_aph->Itinerary->DepTerminal;
            $array['OutFlight'] = $xml_aph->Itinerary->OutFlight;
            $array['RetTerminal'] = $xml_aph->Itinerary->RetTerminal;
            $array['CarReg'] = $xml_aph->CarDetails->CarReg;
            $array['CarMake'] = $xml_aph->CarDetails->CarMake;
            $array['CarModel'] = $xml_aph->CarDetails->CarModel;
            $array['CarColour'] = $xml_aph->CarDetails->CarColour;
            $array['Title'] = $xml_aph->ClientDetails->Title;
            $array['Initial'] = $xml_aph->ClientDetails->Initial;
            $array['Surname'] = $xml_aph->ClientDetails->Surname;
            $array['Telephone1'] = $xml_aph->ClientDetails->Telephone1;
            $array['Telephone2'] = $xml_aph->ClientDetails->Telephone2;
        }
        $array = json_decode(json_encode((array) $array), true);
        $array = $this->array_flatten($array);

        return $array;
    }

    public function AphBooking($data, $airport_code)
    {

        $response = $this->aph_call($data);

        $xml_aph = simplexml_load_string($response);
        $array = [];

        foreach ($xml_aph->CarPark as $key => $item) {

            /*
        		if(!preg_match('/aph/',strtolower($item->CarParkName)) && !preg_match('/silver zone/',strtolower($item->CarParkName)) && !preg_match('/bristol long/',strtolower($item->CarParkName))){
        			unset($item);
        		}
        		*/

            //if(!empty($item)){
            if (isset($item)) {
                //  dd($item);
                $nested = [];
                //$urls = "http://test.parking-quote.co.uk/APH_XML/carparkInfoXML.asp?product_code=".$item->ProductCode;
                //$urls = "http://agents.aph.com/APH_XML/carparkInfoXML.asp?product_code=".$item->CarParkCode."_".$item->ProductCode;
                //$urls = "http://agents.aph.com/APH_XML/carparkInfoXML.asp?product_code=".$item->ProductCode;
                //$urls = "https://test-agents.aph.com/APH_XML/carparkInfoXML.asp?product_code=".$item->CarParkCode."_".$item->ProductCode;
                // 			$urls = config('app.aphurldetails')."?product_code=".$item->CarParkCode."_".$item->ProductCode;

                //agents.aph.com/APH_XML/carparkInfoXML.asp
                // 			$urls = "agents.aph.com/APH_XML/carparkInfoXML.asp?product_code=".$item->CarParkCode."_".$item->ProductCode;
                $urls = $this->_aphdetailurl.$item->ProductCode.'?api_key='.$this->_aphkey;
                $datas = $this->get_aph_data($urls);
                // 			dd($this->_aphdetailurl);
                // 			$prod_info = simplexml_load_string($datas, null, LIBXML_NOCDATA);
                // 			file_put_contents("array_aph_products.txt","Req: ".date('Y-m-d H:i:s')."\r\n".print_r($datas,true),FILE_APPEND);
                if (isset($datas)) {
                    $nested['companyID'] = $item->CarParkCode;
                    $nested['aph_id'] = $item->CarParkCode;
                    $nested['name'] = $item->CarParkName;
                    $nested['ProductCode'] = $item->ProductCode;
                    $nested['parking_type'] = $item->ProductName;
                    $nested['no_of_days'] = $item->Duration;
                    // dd($this->_setting['aph_extra_type']);
                    $companyShare = DB::table('companies')->where('aph_id', $item->CarParkCode)->first();
                    $companyShare = $companyShare->share_percentage;
                    $companyShare = json_decode($companyShare, true);
                    if ($companyShare >= $this->_mysetting['aph_extra_amount']) {
                        $adjustedShare = $companyShare;
                    } else {
                        $difference = $this->_mysetting['aph_extra_amount'] - $companyShare;
                        $adjustedShare = $difference;
                    }
                    if ($this->_mysetting['aph_extra_type'] == 'Percentage') {

                        $this->_addextraAPH = number_format($adjustedShare / 100 * $item->TotalPrice, 2);
                    } else {
                        $this->_addextraAPH = $this->_mysetting['aph_extra_amount'];
                    }
                    $nested['price'] = $item->TotalPrice + $this->_addextraAPH;
                    $nested['EA'] = $this->_addextraAPH;
                    $nested['Terminals'] = $item->Terminals;
                    foreach ($datas->Data as $info) {
                        // dd($info);
                        $nested['Directions'] = $info->Directions.'<br>'.$info->ArrivalInstructions.'<br><h3> Important Information:</h3> '.$info->ImportantInformation.
                                                '<br><h3> Minimum Stay Charge:</h3> '.$info->MinimumStayCharges.'<br> <h3>Disabled Facilities:</h3> '.$info->DisabledFacilities.
                                                '<br><h3> Contact:</h3> '.$info->ArrivalTelephoneNumber.'<br>'.$info->TransferDetails.'<br><h3> Address Line1</h3> '.$info->Address->AddressLine1.
                                                '<br><h3> Address Line2</h3> '.$info->Address->AddressLine2.'<br><h3> Address Line3</h3> '.$info->Address->AddressLine3.'<br>'.$info->Address->Town.'<br><h3> Post Code:</h3> '.$info->Address->Postcode
                                                .'<br><h3> Latitude:</h3> '.$info->Address->Latitude.'<br><h3> Longitude:</h3> '.$info->Address->Longitude.'<br><h3> Parking Option:</h3> '.$info->ParkingOption->Title
                                                .'<br>'.$info->ParkingOption->Description;

                        $nested['MinimumStayCharges'] = $info->MinimumStayCharges;
                        $nested['ParkingRestrictions'] = $info->ParkingRestrictions;
                        $nested['DisabledFacilities'] = $info->DisabledFacilities;

                        $nested['ParkingType'] = $info->ParkingType;

                        $nested['ArrivalTelephoneNumber'] = $info->ArrivalTelephoneNumber;
                        $nested['ReturnTelephoneNumber'] = $info->ReturnTelephoneNumber;
                        $nested['ShortDescription'] = $info->ShortDescription;
                        $nested['ArrivalInstructions'] = $info->ArrivalInstructions;
                        $nested['ReturnInstructions'] = $info->ReturnInstructions;
                        $nested['TransferDetails'] = $info->TransferDetails;
                        $nested['Logo'] = $info->ImageList->Logo;
                        $nested['AddressLine1'] = $info->Address->AddressLine1;
                        $nested['AddressLine2'] = $info->Address->AddressLine2;
                        $nested['AddressLine3'] = $info->Address->AddressLine3;
                        $nested['Town'] = $info->Address->Town;
                        $nested['Postcode'] = $info->Address->Postcode;
                        $nested['Latitude'] = $info->Address->Latitude;
                        $nested['Longitude'] = $info->Address->Longitude;
                        $nested['ImportantInformation'] = $info->ImportantInformation;
                        $nested['ParkingOptionTitle'] = $info->ParkingOption->Title;
                        $nested['ParkingOptionDesc'] = $info->ParkingOption->Description;
                        $nested['ProductName'] = $info->ProductName ?? $item->CarParkName;

                        $nested['desc1'] = $info->ParkingType;
                        $nested['desc2'] = $info->Car_Park_Summary;
                        $nested['direction'] = $info->Directions;
                        $nested['desc3'] = $info->Reason_To_Buy;
                        $nested['miles_from_airport'] = $info->Distance_From_Airport;
                        $nested['travel_time'] = $info->Transfer_To_Airport;
                        // 		$nested['Directions'] = $info->Directions;
                        $nested['arival'] = $info->ArrivalInstructions;
                        $nested['return_proc'] = $info->ReturnInstructions.'<br><h3> Contact:</h3> '.$info->ReturnTelephoneNumber;

                        $nested['awards'] = $info->ParkMark;
                        $commission = number_format($item->Commission / $item->TotalPrice * 100);
                        $specialFeatures = [$info->UspList->Security];
                        $special_features = implode(',', $specialFeatures);
                        if (empty($special_features)) {
                            $special_features = 'CCTV,SECURE BARRIER,DISABILITY FRIENDLY,FAMILY FRENDLY,FENCING,YOU LEAVE YOUR KEYS,BUSINESS FRENDLY';
                        }

                        // 		 dd($airport_code);

                        $airport = DB::table('airports')->where('iata_code', $airport_code)->get();
                        $airport = json_decode($airport, true);

                        $companies = DB::table('companies')->where('aph_id', $nested['companyID'])->get();
                        $companies = json_decode($companies, true);
                        if (isset($info->ParkingOption->Title)) {
                            if ($info->ParkingOption->Title == 'Flex') {
                                $flex = '1';
                            } else {
                                $flex = '0';
                            }

                        }

                        if (empty($companies)) {

                            $companies = DB::insert('insert into companies (
                    company_code,
                    aph_id,
                    name,
                    parking_type,
                    admin_id,
                    arival,
                    arivalfront,
                    MinimumStayCharges,
                    ParkingRestrictions,
                    DisabledFacilities,
                    ArrivalTelephoneNumber,
                    ReturnTelephoneNumber,
                    ShortDescription,
                    return_proc,
                    returnfront,
                    TransferDetails,
                    logo,
                    address,
                    address2,
                    address3,
                    town,
                    post_code,
                    Latitude,
                    Longitude,
                    ImportantInformation,
                    ParkingOptionTitle,
                    ParkingOptionDesc,
                    share_percentage,
                    company_email,
                    max_discount,
                    airport_id,
                    overview,
                    special_features,
                    is_active,
                    is_flex
                    )
                    values
                    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?, ? , ? ,? ,? , ? ,? , ? , ? , ? , ? , ? , ?,?,?,?,?,?,?,?)', [

                                $nested['companyID'],
                                $nested['aph_id'],
                                $nested['ProductName'],
                                $nested['ParkingType'],
                                '34',
                                $nested['Directions'],
                                $nested['Directions'],
                                $nested['MinimumStayCharges'],
                                $nested['ParkingRestrictions'],
                                $nested['DisabledFacilities'],
                                $nested['ArrivalTelephoneNumber'],
                                $nested['ReturnTelephoneNumber'],
                                $nested['ShortDescription'],
                                $nested['return_proc'],
                                $nested['return_proc'],
                                $nested['TransferDetails'],
                                $nested['Logo'],
                                $nested['AddressLine1'],
                                $nested['AddressLine2'],
                                $nested['AddressLine3'],
                                $nested['Town'],
                                $nested['Postcode'],
                                $nested['Latitude'],
                                $nested['Longitude'],
                                $nested['ImportantInformation'],
                                $nested['ParkingOptionTitle'],
                                $nested['ParkingOptionDesc'],
                                $commission,
                                'customerservices@aph.com',
                                '30',
                                $airport[0]['id'],
                                $nested['Directions'],
                                $special_features,
                                'Yes',
                                $flex,
                            ]);

                        }

                        $c_id = DB::table('companies')->where('aph_id', $nested['aph_id'])->get();

                        $c_id = json_decode($c_id, true);
                        $c_id = $c_id[0]['id'];

                        $facilities = DB::table('facilities')->where('company_id', $c_id)->get();

                        $facilities = json_decode($facilities, true);

                        if (empty($facilities)) {

                            $facis = $info->UspList->CarParkFacility;

                            foreach ($facis as $fac) {

                                $up_fac = DB::insert('insert into facilities (company_id,description,type) values (?, ?, ? )',
                                    [$c_id, $fac, 'company']);

                            }

                        }

                    }

                    $nested = json_decode(json_encode((array) $nested), true);

                    //$nested = array_flatten($nested);
                    //$array[] = array_flatten($nested);

                    $nested = $this->array_flatten($nested);
                    $array[] = $this->array_flatten($nested);
                }
            }//
        }

        //file_put_contents("array_aph_companies.txt","Req: ".date('Y-m-d H:i:s')."\r\n".print_r($array,true),FILE_APPEND);
        return $array;
    }

    public function aph_call($data)
    {
        //$url = "http://test.parking-quote.co.uk/xmlapi/aphxml.ASP";
        //$url = "http://agents.aph.com/xmlapi/aphxml.ASP";
        //$url = "https://test-agents.aph.com/xmlapi/aphxml.ASP";
        $url = config('app.aphurl');
        set_time_limit(120);
        $output = [];
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_HEADER, 0);
        curl_setopt($curlSession, CURLOPT_POST, 1);
        curl_setopt($curlSession, CURLOPT_POSTFIELDS, 'Request='.$data);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlSession, CURLOPT_TIMEOUT, 60);
        //$response = split(chr(10),curl_exec ($curlSession));
        $response = curl_exec($curlSession);
        if (curl_errno($curlSession)) {
            echo curl_error($curlSession);
        }
        curl_close($curlSession);
        // Log::info('aph booking order: '.$response);
        // 	file_put_contents("amir_aph_response.txt","Req: ".date('Y-m-d H:i:s')."\r\n".$data." RES: \r\n".date('Y-m-d H:i:s')."\r\n".$response."\r\n\r\n",FILE_APPEND);

        // 	$response = '<API_Reply
        // 					System="APH"
        // 					Version="1.0"
        // 					Product="CarPark"
        // 					Customer="A"
        // 					Session="000000003"
        // 					RequestCode="11"
        // 					Result="OK">

        // 						<CarPark c="1">
        // 							<CarParkCode>LGW8</CarParkCode>
        // 							<CarParkName>Meet + Greet LGW8</CarParkName>
        // 							<ProductCode>LGWP</ProductCode>
        // 							<ProductName>Meet and Greet Return</ProductName>
        // 							<Duration>8</Duration>
        // 							<TotalPrice>81.00</TotalPrice>
        // 							<GatePrice>85.00</GatePrice>
        // 							<Commission>8.5</Commission>
        // 							<Terminals>N,S,</Terminals>
        // 						</CarPark>

        // 						<CarPark c="2">
        // 							<CarParkCode>LGW8</CarParkCode>
        // 							<CarParkName>Meet + Greet LGW8</CarParkName>
        // 							<ProductCode>LGWQ</ProductCode>
        // 							<ProductName>Meet and Greet Early Bird</ProductName>
        // 							<Duration>8</Duration>
        // 							<TotalPrice>52.80</TotalPrice>
        // 							<GatePrice>56.80</GatePrice>
        // 							<Commission>5.68</Commission>
        // 							<Terminals>N,S,</Terminals>

        // 						</CarPark>

        // 				</API_Reply>
        // ';

        return $response;
    }

    public function array_flatten($array)
    {
        if (! is_array($array)) {
            return false;
        }
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $arrayList = $this->array_flatten($value);
                foreach ($arrayList as $listItem) {
                    $result[$key] = $listItem;
                }
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function get_aph_data($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'json',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        // 	file_put_contents("detail_aph_response.txt","Req2: ".date('Y-m-d H:i:s')."\r\n".$url." RES2: \r\n".date('Y-m-d H:i:s')."\r\n".$response."\r\n\r\n",FILE_APPEND);

        return $response;
    }

    // function get_aph_data($url) {
    // 	$ch = curl_init();
    // 	$timeout = 5;
    // 	curl_setopt($ch, CURLOPT_URL, $url);
    // 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    // 	$data = curl_exec($ch);
    // 	curl_close($ch);

    // 	file_put_contents("detail_aph_response.txt","Req2: ".date('Y-m-d H:i:s')."\r\n".$url." RES2: \r\n".date('Y-m-d H:i:s')."\r\n".$data."\r\n\r\n",FILE_APPEND);

    // 	return $data;
    // }

    public function GlobalBooking($airport_id, $from_date, $from_time, $to_date, $to_time, $id)
    {
        $url = 'https://live-api.opitech.co.uk/api-search.php?';
        $getString = 'location_code='.$airport_id;
        $getString .= '&arrival_date='.$from_date.'&arrival_time='.$from_time;
        $getString .= '&depart_date='.$to_date.'&depart_time='.$to_time.'&agentCode=PARZ&key=GzJew9QjhzzcOsSdq4u0jpInT5xNQSA0';
        $final = $url.$getString;

        $result = $this->curl_call($final);

        $result = json_decode($result, true);

        //echo "<pre>"; print_r($result['data']); echo "</pre>"; exit;
        //$result = Search_IN_ARRAY($result, 'status', 'Enabled');
        $data = $this->global_list($result, $id);

        return $data;
    }

    public function OpitechBooking($airport_id, $from_date, $from_time, $to_date, $to_time, $id)
    {
        $url = 'https://live-api.opitechdevelopment.com/api-search.php?';
        $getString = 'location_code='.$airport_id;
        $getString .= '&arrival_date='.$from_date.'&arrival_time='.$from_time;
        $getString .= '&depart_date='.$to_date.'&depart_time='.$to_time.'&agentCode=PARZ&key=hg563hrfs4J3FyLn6n3UypQo4ZST5zNQJ';
        $final = $url.$getString;

        $result = $this->curl_call($final);

        $result = json_decode($result, true);

        //dd($result);
        //echo "<pre>"; print_r($result['data']); echo "</pre>"; exit;
        //$result = Search_IN_ARRAY($result, 'status', 'Enabled');
        $data = $this->opitech_list($result, $id);

        return $data;
    }

    public function opitech_list($data, $id)
    {

        $array = [];
        $nested = [];
        if ($data['status'] == 'OK') {
            foreach ($data['results'] as $item) {

                //$nested['id'] = $item['id'];
                $nested['price'] = $item['product']['price'];
                $nested['name'] = $item['product']['name'];
                $nested['quote'] = $item['product']['quote'];
                $nested['token'] = $item['product']['token'];
                $nested['airport'] = $id;

                $url = 'https://live-api.opitechdevelopment.com/api-product.php?product_code='.$item['product']['code'].'&agentCode=PARZ&key=hg563hrfs4J3FyLn6n3UypQo4ZST5zNQJ';
                $result = $this->curl_call($url);
                $result = json_decode($result, true);
                //   dd($result);
                $p_code = 'OP-'.$result['product']['product_code'];
                $nested['sku'] = $p_code;
                $nested['logo'] = $result['product']['logo'];

                if ($result['product']['meet_and_greet'] != '1') {
                    $type = 'Park and Ride';
                } else {
                    $type = 'Meet and Greet';
                }

                $airport = DB::table('airports')->where('iata_code', $data['request']['location_code'])->get();
                $airport = json_decode($airport, true);
                // return $result['product']['meet_and_greet'];
                $companies = DB::table('companies')->where('company_code', $p_code)->get();
                $companies = json_decode($companies, true);
                //dd($companies);
                if (empty($companies)) {
                    $arival = $result['product']['arrival_procedures'];
                    $arivalfront = $result['product']['arrival_procedures'];
                    $return_proc = $result['product']['departure_procedures'];
                    $returnfront = $result['product']['departure_procedures'];
                    $arival = str_replace("you'll", 'you will', $arival);
                    $arivalfront = str_replace("you'll", 'you will', $arivalfront);
                    $arival = str_replace("it's", 'it is', $arival);
                    $arivalfront = str_replace("it's", 'it is', $arivalfront);
                    $arival = str_replace("you're", 'you are', $arival);
                    $arivalfront = str_replace("you're", 'you are', $arivalfront);
                    $arival = str_replace("you've", 'you have', $arival);
                    $arivalfront = str_replace("you've", 'you have', $arivalfront);
                    $return_proc = str_replace("you're", 'you are', $return_proc);
                    $returnfront = str_replace("you're", 'you are', $returnfront);
                    $return_proc = str_replace("you've", 'you have', $return_proc);
                    $returnfront = str_replace("you've", 'you have', $returnfront);
                    $special_features = $result['product']['security_measures'] ?? 'CCTV,SECURE BARRIER,DISABILITY FRIENDLY,FAMILY FRENDLY,FENCING,BUSINESS FRENDLY,24/7 Security Guards,Fencing';
                    $companies = DB::insert('insert into companies (name,company_code,admin_id,company_email,airport_id,parking_type,logo,overview,arival,arivalfront,
                return_proc,returnfront,address,miles_from_airport,special_features,is_active) values (?, ?, ? , ? ,? ,? , ? ,? , ? , ? , ? , ? , ? , ?,?,?)',
                        [$result['product']['name'], $p_code, '49', 'opitech@parkingzone.co.uk', $airport[0]['id'], $type,
                            $result['product']['logo'], $result['product']['introduction'], $arival, $arival,
                            $return_proc, $return_proc, $result['product']['address'], $result['product']['distance_miles'],
                            $special_features, 'Yes']);

                }
                //dd($result);
                $c_id = DB::table('companies')->where('company_code', $p_code)->get();
                $c_id = json_decode($c_id, true);
                $c_id = $c_id[0]['id'];
                $facilities = DB::table('facilities')->where('company_id', $c_id)->get();
                $facilities = json_decode($facilities, true);
                $sell_point_1 = $result['product']['sell_point_1'] ?? 'Excellent value for money';
                $sell_point_2 = $result['product']['sell_point_2'] ?? 'Fully secure with CCTV';
                $sell_point_3 = $result['product']['sell_point_3'] ?? 'Open 24/7';
                $sell_point_4 = $result['product']['sell_point_4'] ?? 'Automated entry and exit.';

                if (empty($facilities)) {
                    $facis = [$sell_point_1, $sell_point_2, $sell_point_3, $sell_point_4];

                    foreach ($facis as $fac) {

                        $up_fac = DB::insert('insert into facilities (company_id,description,type) values (?, ?, ? )',
                            [$c_id, $fac, 'company']);

                    }

                } else {

                }

                $array[] = $this->array_flatten($nested);

            }
        }
        $array = json_decode(json_encode((array) $array), true);

        return $array;
    }

    public function GlobalSingle($sku, $from_date, $from_time, $to_date, $to_time)
    {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
        $from_time = date('H:i', strtotime($from_time));
        $to_time = date('H:i', strtotime($to_time));

        $url = 'https://maple.use-fuse.com/api/package';
        $getString = '?user=flyparkplus&userkey=59e6322b3885b';
        $getString .= '&sku='.$sku;
        $getString .= '&from='.$from_date.'%20'.$from_time;
        $getString .= '&to='.$to_date.'%20'.$to_time;
        $final = $url.$getString;
        $result = $this->curl_call($final);
        $result = json_decode($result, true);
        $data = $result['DATA'][0]['price'];

        return $data;
    }

    public function global_list($data, $id)
    {

        $array = [];
        $nested = [];
        if ($data['status'] == 'OK') {
            foreach ($data['results'] as $item) {

                //$nested['id'] = $item['id'];
                $nested['price'] = $item['product']['price'];
                $nested['sku'] = $item['product']['code'];
                $nested['name'] = $item['product']['name'];
                $nested['quote'] = $item['product']['quote'];
                $nested['token'] = $item['product']['token'];
                $nested['airport'] = $id;

                $url = 'https://live-api.opitech.co.uk/api-product.php?product_code='.$item['product']['code'].'&agentCode=PARZ&key=GzJew9QjhzzcOsSdq4u0jpInT5xNQSA0';
                $result = $this->curl_call($url);
                $result = json_decode($result, true);

                if ($result['product']['meet_and_greet'] != '1') {
                    $type = 'Park and Ride';
                } else {
                    $type = 'Meet and Greet';
                }

                //dd($result);
                $airport = DB::table('airports')->where('iata_code', $data['request']['location_code'])->get();
                $airport = json_decode($airport, true);
                // return $result['product']['meet_and_greet'];
                $companies = DB::table('companies')->where('company_code', $item['product']['code'])->get();
                $companies = json_decode($companies, true);

                if (empty($companies)) {
                    $arival = $result['product']['arrival_procedures'];
                    $arivalfront = $result['product']['arrival_procedures'];
                    $return_proc = $result['product']['departure_procedures'];
                    $returnfront = $result['product']['departure_procedures'];
                    $arival = str_replace("you'll", 'you will', $arival);
                    $arivalfront = str_replace("you'll", 'you will', $arivalfront);
                    $arival = str_replace("it's", 'it is', $arival);
                    $arivalfront = str_replace("it's", 'it is', $arivalfront);
                    $arival = str_replace("you're", 'you are', $arival);
                    $arivalfront = str_replace("you're", 'you are', $arivalfront);
                    $arival = str_replace("you've", 'you have', $arival);
                    $arivalfront = str_replace("you've", 'you have', $arivalfront);
                    $return_proc = str_replace("you're", 'you are', $return_proc);
                    $returnfront = str_replace("you're", 'you are', $returnfront);
                    $return_proc = str_replace("you've", 'you have', $return_proc);
                    $returnfront = str_replace("you've", 'you have', $returnfront);
                    $special_features = $result['product']['security_measures'] ?? 'CCTV,SECURE BARRIER,DISABILITY FRIENDLY,FAMILY FRENDLY,FENCING,BUSINESS FRENDLY,24/7 Security Guards,Fencing';
                    $companies = DB::insert('insert into companies (name,company_code,admin_id,company_email,airport_id,parking_type,logo,overview,arival,arivalfront,
                return_proc,returnfront,address,miles_from_airport,special_features,is_active,share_percentage) values (?, ?, ? , ? ,? ,? , ? ,? , ? , ? , ? , ? , ? , ?,?,?,?)',
                        [$result['product']['name'], $result['product']['product_code'], '37', 'agent_bookings@opitechdevelopment.com', $airport[0]['id'], $type,
                            $result['product']['logo'], $result['product']['introduction'], $arival, $arival,
                            $return_proc, $return_proc, $result['product']['address'], $result['product']['distance_miles'],
                            $special_features, 'Yes', '30']);
                    $c_id = DB::table('companies')->where('company_code', $nested['sku'])->get();
                    $c_id = json_decode($c_id, true);
                    $c_id = $c_id[0]['id'];
                    $facilities = DB::table('facilities')->where('company_id', $c_id)->get();
                    $facilities = json_decode($facilities, true);

                    $sell_point_1 = $result['product']['sell_point_1'] ?? 'Excellent value for money';
                    $sell_point_2 = $result['product']['sell_point_2'] ?? 'Fully secure with CCTV';
                    $sell_point_3 = $result['product']['sell_point_3'] ?? 'Open 24/7';
                    $sell_point_4 = $result['product']['sell_point_4'] ?? 'Automated entry and exit.';

                    if (empty($facilities)) {
                        $facis = [$sell_point_1, $sell_point_2, $sell_point_3, $sell_point_4];

                        foreach ($facis as $fac) {

                            $up_fac = DB::insert('insert into facilities (company_id,description,type) values (?, ?, ? )',
                                [$c_id, $fac, 'company']);

                        }

                    }
                } else {

                }

                $array[] = $this->array_flatten($nested);

            }
        }
        $array = json_decode(json_encode((array) $array), true);

        return $array;
    }

    public function HolidayExtraBooking($airport_code, $from_date, $from_time, $to_date, $to_time)
    {

        $from_date = date('Y-m-d', strtotime($from_date));

        $to_date = date('Y-m-d', strtotime($to_date));
        $from_time = date('Hi', strtotime($from_time));
        $to_time = date('Hi', strtotime($to_time));

        $url = 'https://api.holidayextras.co.uk/v1/carpark/'.$airport_code.'.js';
        $getString = '?ABTANumber=AJ166&Password=PAXML&Initials=pa&key=parkingzone&token=829152491';
        $getString .= '&ArrivalDate='.$from_date.'&ArrivalTime='.$from_time;
        $getString .= '&DepartDate='.$to_date.'&DepartTime='.$to_time;
        $final = $url.$getString;
        $result = $this->curl_call($final);

        $result = json_decode($result, true);
        $data = $this->holiday_list($result);

        //echo "<pre>"; print_r($data); echo "</pre>"; exit;
        return $data;
    }

    public function holiday_list($data)
    {
        $array = [];
        $nested = [];
        foreach ($data['API_Reply']['CarPark'] as $item) {
            //$nested['id'] = $item['id'];
            //  dd($this->_addextra);
            $companyShare = DB::table('companies')->where('company_code', $item['Code'])->first();
            $companyShare = $companyShare->share_percentage;
            $companyShare = json_decode($companyShare, true);
            if ($companyShare >= $this->_mysetting['holiday_extra_amount']) {
                $adjustedShare = $companyShare;
            } else {
                $difference = $this->_mysetting['holiday_extra_amount'] - $companyShare;
                $adjustedShare = $difference;
            }

            //  dd('original = '.$companyShare.'adjusted = '.$adjustedShare);

            if ($this->_mysetting['holiday_extra_type'] == 'Percentage') {

                $this->_addextra = number_format($adjustedShare / 100 * $item['TotalPrice'], 2);
            }

            $nested['price'] = $item['TotalPrice'] + $this->_addextra;
            // 			dd($item['TotalPrice']," and added price: ",$nested['price'] );
            $nested['EA'] = $this->_addextra;
            $nested['sku'] = $item['Code'];
            $nested['name'] = $item['Name'];
            $nested['booking_url'] = $item['BookingURL'];
            $nested['moreinfo_url'] = $item['MoreInfoURL'];
            $array[] = $this->array_flatten($nested);
        }
        $array = json_decode(json_encode((array) $array), true);

        return $array;
    }

    public function HolidayBookingPrice($from_date, $to_date, $from_time, $to_time, $company_id, $passenger, $product_code)
    {

        $from_date = date('Y-m-d', strtotime($from_date));

        $to_date = date('Y-m-d', strtotime($to_date));
        $from_time = date('Hi', strtotime($from_time));
        $to_time = date('Hi', strtotime($to_time));

        $url = 'https://api.holidayextras.co.uk/v1/carpark/'.$product_code;
        $getString = '?ABTANumber=AJ166&Password=PAXML&Initials=pa&key=parkingzone&token=829152491';
        $getString .= '&ArrivalDate='.$from_date.'&ArrivalTime='.$from_time;
        $getString .= '&DepartDate='.$to_date.'&DepartTime='.$to_time.'&NumberOfPax='.$passenger;
        $final = $url.$getString;

        $response = $this->curl_call($final);
        $xml_aph = simplexml_load_string($response);
        $xml_aph = json_decode(json_encode((array) $xml_aph));

        $booking_price = $xml_aph->Pricing->TotalPrice;

        return $booking_price;
    }

    public function HolidayBookingOrder($data, $product_code)
    {

        $url = 'https://api.holidayextras.co.uk/carpark/'.$product_code;
        $data = 'ABTANumber=AJ166&Password=PAXML&Initials=pa&key=parkingzone&token=829152491'.$data;
        $response = $this->holidaybooking_call($data, $url);

        $xml_aph = simplexml_load_string($response);

        $xml_aph = json_decode(json_encode((array) $xml_aph));

        $array = [];

        $array['BookingRef'] = $xml_aph->Booking->BookingRef;
        $array['ArrivalDate'] = $xml_aph->CarPark->ArrivalDate;
        $array['DepartDate'] = $xml_aph->CarPark->DepartDate;
        $array['ArrivalTime'] = $xml_aph->CarPark->ArrivalTime;
        $array['DepartTime'] = $xml_aph->CarPark->DepartTime;
        $array['no_of_days'] = $xml_aph->CarPark->Duration;
        $array['CarParkCode'] = $xml_aph->CarPark->Code;
        $array['NumberOfPax'] = $xml_aph->CarPark->NumberOfPax;
        $array['CarReg'] = $xml_aph->CarDetails->Registration;
        $array['CarMake'] = $xml_aph->CarDetails->CarMake;
        $array['CarModel'] = $xml_aph->CarDetails->CarModel;
        $array['CarColour'] = $xml_aph->CarDetails->CarColour;
        $array['Title'] = $xml_aph->ClientDetails->Title;
        $array['Initial'] = $xml_aph->ClientDetails->Initial;
        $array['Surname'] = $xml_aph->ClientDetails->Surname;
        $array['Email'] = $xml_aph->ClientDetails->Email;
        $array['MoreInfoURL'] = $xml_aph->MoreInfoURL;

        // 		$array = json_decode(json_encode((array)$array), TRUE);
        // 		$array = $this->array_flatten($array);
        return $array;

    }

    public function HolidayExtraLounges($airport_code, $checkin_date, $checkin_time, $adult, $children)
    {

        $url = 'https://api.holidayextras.co.uk/v1/lounge/'.$airport_code.'.js';
        $getString = '?ABTANumber=AJ166&Password=PAXML&Initials=pa&key=parkingzone&token=829152491';
        $getString .= '&ArrivalDate='.$checkin_date.'&ArrivalTime='.$checkin_time;
        $getString .= '&Adults='.$adult.'&Children='.$children;
        $final = $url.$getString;
        $result = $this->curl_call($final);
        $result = json_decode($result, true);

        //echo "<pre>"; print_r($result); echo "</pre>"; exit;

        $data = $this->holiday_list_lounge($result);

        //echo "<pre>"; print_r($data); echo "</pre>"; exit;
        return $data;
    }

    public function holiday_list_lounge($data)
    {
        $array = [];
        $nested = [];
        foreach ($data['API_Reply']['Lounge'] as $item) {
            $nested['price'] = $item['Price'];
            $nested['sku'] = $item['Code'];
            $nested['name'] = $item['Name'];
            $nested['booking_url'] = $item['BookingURL'];
            $nested['moreinfo_url'] = $item['MoreInfoURL'];
            $nested['terminal'] = $item['terminal'];
            $array[] = $this->array_flatten($nested);
        }
        $array = json_decode(json_encode((array) $array), true);

        return $array;
    }

    public function HolidayBookingLoungePrice($product_code, $checkin_date, $checkin_time, $adult, $children)
    {

        $checkin_date = str_replace('/', '-', $checkin_date);
        $checkin_date = date('Y-m-d', strtotime($checkin_date));

        $checkin_time = date('Hi', strtotime($checkin_time));

        $url = 'https://api.holidayextras.co.uk/v1/lounge/'.$product_code;
        $getString = '?ABTANumber=AJ166&Password=PAXML&Initials=pa&key=parkingzone&token=829152491';
        $getString .= '&ArrivalDate='.$checkin_date.'&ArrivalTime='.$checkin_time;
        $getString .= '&Adults='.$adult.'&Children='.$children;
        $final = $url.$getString;
        //exit;

        $response = $this->curl_call($final);
        $xml_aph = simplexml_load_string($response);
        $xml_aph = json_decode(json_encode((array) $xml_aph));

        $booking_price = $xml_aph->Pricing->TotalPrice;

        return $booking_price;
    }

    public function HolidayBookingOrderLounge($data, $product_code)
    {

        $url = 'https://api.holidayextras.co.uk/v1/lounge/HP'.$product_code;

        $data = 'ABTANumber=AJ166&Password=PAXML&Initials=pa&key=parkingzone&token=829152491'.$data;

        $response = $this->holidaybooking_call($data, $url);

        $xml_aph = simplexml_load_string($response);

        $xml_aph = json_decode(json_encode((array) $xml_aph));

        //echo "<pre>"; print_r($xml_aph); echo "</pre>"; exit;
        $array = [];

        $array['BookingRef'] = $xml_aph->Booking->BookingRef;
        $array['MoreInfoURL'] = $xml_aph->Booking->MoreInfoURL;
        $array['AgentComm'] = $xml_aph->Booking->AgentComm;

        // 		$array = json_decode(json_encode((array)$array), TRUE);
        // 		$array = $this->array_flatten($array);
        return $array;

    }

    public function HolidayBookingOrderTransfer($data, $booking_url)
    {

        $url = 'https://api.holidayextras.co.uk/'.$booking_url;

        $data = 'ABTANumber=AJ166&Password=PAXML&key=parkingzone&token=829152491'.$data;
        //exit;

        $response = $this->holidaybooking_call_transfer($data, $url);

        $result = json_decode($response, true);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
        exit;
        $array = [];

        $array['BookingRef'] = $xml_aph->Booking->BookingRef;
        $array['MoreInfoURL'] = $xml_aph->Booking->MoreInfoURL;
        $array['AgentComm'] = $xml_aph->Booking->AgentComm;

        // 		$array = json_decode(json_encode((array)$array), TRUE);
        // 		$array = $this->array_flatten($array);
        return $array;

    }

    public function HolidayExtraTransfer($data)
    {

        $arrival_date = str_replace('/', '-', $data['arrival_date']);
        $arrival_date = date('Y-m-d', strtotime($arrival_date));
        $arrival_time = date('Hi', strtotime($data['arrival_time']));

        $return_date = str_replace('/', '-', $data['return_date']);
        $return_date = date('Y-m-d', strtotime($return_date));
        $return_time = date('Hi', strtotime($data['return_time']));

        $url = 'https://api.holidayextras.co.uk/v1/transfers/search.js';
        $getString = '?ABTANumber=AJ166&Password=PAXML&Initials=pa&key=parkingzone&token=829152491';
        $getString .= '&PickUp='.$data['loc_code'].'&PickUpType='.$data['loc_type'];
        $getString .= '&DropOff='.$data['loc_code_drop'].'&DropOffType='.$data['loc_type_drop'];
        $getString .= '&FromDate='.$arrival_date.'&FromTime='.$arrival_time;
        $getString .= '&ReturnDate='.$return_date.'&ReturnTime='.$return_time;
        $getString .= '&Adults='.$data['adults'].'&Children='.$data['children'];
        $final = $url.$getString;
        $result = $this->curl_call($final);
        $result = json_decode($result, true);

        //echo "<pre>"; print_r($result['API_Reply']['Transfers']); echo "</pre>"; exit;

        $resp = $result['API_Reply']['Transfers'];

        //echo "<pre>"; print_r($resp); echo "</pre>"; exit;
        return $resp;
    }

    public function curl_call($url)
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

    public function holidaybooking_call_transfer($data, $url)
    {

        set_time_limit(120);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        // 	file_put_contents("holiday_booking_response.txt","Req: ".date('Y-m-d H:i:s')."\r\n".$data." RES: \r\n".date('Y-m-d H:i:s')."\r\n".$response."\r\n\r\n",FILE_APPEND);

        return $response;
    }

    public function holidaybooking_call($data, $url)
    {

        set_time_limit(120);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        // Log::info('Holiday Order: '.$response);

        // 	file_put_contents("holiday_booking_response.txt","Req: ".date('Y-m-d H:i:s')."\r\n".$data." RES: \r\n".date('Y-m-d H:i:s')."\r\n".$response."\r\n\r\n",FILE_APPEND);

        return $response;
    }
}
