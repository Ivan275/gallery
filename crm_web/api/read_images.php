<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include database and image file
include_once '../includes/database.php';
//include_once '../includes/image.php';

//instantiate database and image object
$database2 = new Mydatabase();
$db = $database2->open_connection2();
//print_r($db);


 $query1 = "SELECT * FROM photograph";
 $result = $db->query($query1);

$query2 = "SELECT
                id, filename, type, size, caption
            FROM
                photograph";

$stmt = $db->query($query2);
 

if($result->num_rows>0){
 
    // images array
    $images_arr=array();
    $images_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = mysqli_fetch_array($stmt)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $image_item=array(
            "id" => $id,
            "filename" => $filename,
            "type" => $type,
            "size" => $size,
            "caption" => $caption,
        );
 
        array_push($images_arr["records"], $image_item);
    }
 
    echo json_encode($images_arr);
}
 
else{
    echo json_encode(
        array("message" => "No images found.")
    );
}

?>