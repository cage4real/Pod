<?php
  header('Content-type: application/json');
  include('../functions/functions.php');
  //Receiveing Input in Json and decoding
  basic_authentication($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
  $data = json_decode(file_get_contents('php://input'));
  $type = $data->{"type"};
  $lat = $data->{"latitude"};
  $long = $data->{"longitude"};
  $search_content = $data->{"search_content"};
  $result = array();
  //Basic Validation  
	if($type == 'cuisine')
	   if(empty($search_content)){
          $data = get_all_data('restaurant_cuisine');
	   }
	   else
	   {
	   	$data = mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_cuisine WHERE cuisine_name LIKE '%".
	   		mysqli_real_escape_string($conn,trim($search_content))."%'");
             
	   }	
	else if($type == 'dietary')
	    if(empty($search_content)){
          $data = get_all_data('restaurant_dietary');
	    }
	    else
	     {
	   	  $data = mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_dietary WHERE dietary_name LIKE '%".mysqli_real_escape_string($conn,$search_content)."%'");
         }
	else if($type == 'ambience')
		 if(empty($search_content)){
          $data = get_all_data('restaurant_ambience');
	     }
	    else
	    {
	      $data = mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_ambience WHERE ambience_name LIKE '%".mysqli_real_escape_string($conn,$search_content)."%'");
	      
        }
		
	else if($type == 'meal')
		 if(empty($search_content)){
           $data = get_all_data(' meals');
	     }
	     else
	     {
	    	$data = mysqli_query($GLOBALS['conn'],"SELECT * FROM  meals WHERE meal_name LIKE '%".mysqli_real_escape_string($conn,trim($search_content))."%'");      
	     }	
	else if($type == 'location')
		if(empty($search_content))
		{
			 $data = mysqli_query($GLOBALS['conn'],"SELECT *,( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($long) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM restaurant_location Order By distance"); 
		}
		else
		{
            $data = mysqli_query($GLOBALS['conn'],"SELECT * ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($long) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM restaurant_location WHERE location LIKE '%".mysqli_real_escape_string($conn,$search_content)."%' Order By distance");    
		}
    else if($type == 'price') 
    	if(empty($search_content))
    	{

            $data = mysqli_query($GLOBALS['conn'],"SELECT * FROM restaurant_price_range WHERE price_range LIKE '%".mysqli_real_escape_string($conn,$search_content)."%'");
    	}
    	else
    	{
    		$data = get_all_data('restaurant_price_range');
    	}
     else
    	$data=""; 
      if($data){
    	while($record = mysqli_fetch_assoc($data)){
		$allData['id'] = $record['id'];
		if($type == 'cuisine')
		{
			$allData['name'] = $record['cuisine_name'];
		}
		else if($type == 'dietary')
		{
			$allData['name'] = $record['dietary_name'];
		}
		else if($type == 'ambience')
		{
			$allData['name'] = $record['ambience_name'];
		}
		else if($type == 'meal')
		{
			$allData['name'] = $record['meal_name'];
		}
		else if($type == 'location')
		{
			$allData['name'] = $record['location'];
		    $allData['distance'] = round($record['distance'], 2).' '.Miles;

		}	
		else
		{
			$allData['name'] = $record['price_range'];	
		}
		$result[] = $allData;
	   }
   
    } 
	
		$response['allList'] = $result;
		$response['responseCode'] = 200;
		$response['responseMessage'] = 'All list';
 
  //Sending response after json encoding
  $responseJson = json_encode($response);
  print $responseJson;


  
?>
