<?php

 header('Content-type: application/json');
 include('../functions/functions.php');
//Receiveing Input in Json and decoding
 basic_authentication($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
 $data       = json_decode(file_get_contents('php://input'));
 $lat   = $data->{"latitude"};
 $long = $data->{"longitude"};
 $pageSize   = $data->{"page_size"};
 $pageNumber = $data->{"page_number"};
 $restaurant_data   = mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_details LEFT JOIN restaurant_menu_details ON restaurant_details.restaurant_id = restaurant_menu_details.restaurant_id");
 if ($restaurant_data) {
    $restaurant_rows    = mysqli_num_rows($restaurant_data);
    $maxPageNumber = ceil($restaurant_rows / $pageSize);
    $minLimit      = ($pageNumber - 1) * $pageSize;
    $restaurant_list = mysqli_query($GLOBALS['conn'],"SELECT *, ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($long) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance,restaurant_details.restaurant_id as id FROM restaurant_details LEFT JOIN restaurant_menu_details ON restaurant_details.restaurant_id = restaurant_menu_details.restaurant_id ORDER BY distance Limit $minLimit, $pageSize");
    $rows=array();
   
    while ($restaurant_data = mysqli_fetch_array($restaurant_list)) {
        
        $row['restaurant_id']        = $restaurant_data['id'];
        $row['restaurant_name']      = $restaurant_data['restaurant_name'];
        $row['location']             = $restaurant_data['location'];
        $row['postcode']             = $restaurant_data['postcode'];
        $row['latitude']             = $restaurant_data['latitude'];
        $row['longitude']            = $restaurant_data['longitude'];
        $row['restaurant_images']    = $restaurant_data['restaurant_images'];
        $row['deliver_food']         = $restaurant_data['deliver_food'];
        $row['opening_time']         = $restaurant_data['opening_time'];
        $row['closing_time ']        = $restaurant_data['closing_time'];
        $row['about_text']           = $restaurant_data['about_text'];
        $row['max_people_allowed']   = $restaurant_data['max_people_allowed'];
        $cuisine_data                = explode(",",$restaurant_data['cuisine']);
        $cuisine_rows=array();
        if($cuisine_data)
        {
            foreach($cuisine_data as $value)
            {
               $cuisine_name = mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_cuisine WHERE id = '".$value."'"));
                $cuisine_rows[] = $cuisine_name;
            }
        }
        $row['cuisine']              = $cuisine_rows;
        $ambience_data                = explode(",",$restaurant_data['ambience']);
        $ambience_rows=array();
        if($ambience_data)
        {
            foreach($ambience_data as $value)
            {
               $ambience_name = mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_ambience WHERE id = '".$value."'"));
                $ambience_rows[] = $ambience_name;
            }
        }
        $row['ambience']              = $ambience_rows;
        $dietary_data                = explode(",",$restaurant_data['dietary']);
        $dietary_rows=array();
        if($dietary_data)
        {
            foreach($dietary_data as $value)
            {
               $dietary_name = mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_dietary WHERE id = '".$value."'"));
                $dietary_rows[] = $dietary_name;
            }
        }
        $row['dietary']              = $dietary_rows;
        $price_range_data                = explode(",",$restaurant_data['price_range']);
        $price_range_rows=array();
        if($price_range_data)
        {
            foreach($price_range_data as $value)
            {
               $price_range_name = mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_price_range WHERE id = '".$value."'"));
                $price_range_rows[] = $price_range_name;
            }
        }
        $row['price_range']              = $price_range_rows;
        $row['distance']             = $restaurant_data['distance'];
        $rows[]                 = $row;
     }

    $pagination['page_number']        = $pageNumber;
    $pagination['page_size']          = $pageSize;
    $pagination['max_page_number']    = $maxPageNumber;
    $pagination['total_record_count'] = $restaurant_rows;
    $response['responseCode']         = 200;
    $response['responseMessage']      = 'Your Restaurant List Fetched Successfully.';
    $response['restaurant_list']      =$rows;
    $response['pagination']           = $pagination;
} else {
    $response['responseCode']    = 200;
    $response['responseMessage'] = 'No Records.';
}


//Sending response after json encoding
$responseJson = json_encode($response);
print $responseJson;

?>
