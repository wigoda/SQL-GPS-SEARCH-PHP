<?php
header('content-type: application/json; charset=utf-8');

//Database Connection
require_once 'db.php';
 

IF (isset($argv)) {
    $inputUser = $argv[1];
    $inputLatitude = $argv[2];
    $inputLongitude = $argv[3];
    $inputDistance = $argv[4];
}
else {
    $inputUser = $_GET['anon'];
    $inputLatitude = $_GET['latitude'];
    $inputLongitude = $_GET['longitude'];
    $inputDistance = $_GET['distance'];
}

// This SQL statement selects ONLY product data 
// from the 'storeProduct' table for the stores 
// within the user shopping area 
// defined by a latitude/latitude pair and distance (radius)

$sql = " SELECT product.id, product.productName, product.brandDesc, product.productUrl, product.imageSmallUrl, product.imagelargeUrl, ( 3959 * acos( cos( radians($inputLatitude) ) * cos( radians( storeLatitude ) ) * cos( radians( storeLongitude ) - radians($inputLongitude) ) + sin( radians($inputLatitude) ) * sin( radians( storeLatitude ) ) ) ) AS distance FROM store, storeProduct, product WHERE storeProduct.storeId = store.id AND product.id = storeProduct.productId HAVING distance < $inputDistance ORDER BY distance"; 

// Check if there are results
IF ($result = mysqli_query($con, $sql))
{
    // If so, then create a results array and a temporary one
    // to hold the data
    $resultArray = array();
    $tempArray = array();
 
    // Loop through each row in the result set
    WHILE ($row = $result->fetch_object())
    {
        // Add each row into our results array
        $tempArray = $row;
        array_push($resultArray, $tempArray);
    }
 
    // Finally, encode the array to JSON and output the results
    // echo json_encode($resultArray,JSON_UNESCAPED_UNICODE);
    echo json_encode($resultArray);
}
 
// Close connections
mysqli_close($con);
?>

