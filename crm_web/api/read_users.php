<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include database and user file
include_once '../includes/database.php';
//include_once '../includes/user.php';

//instantiate database and user object
$database2 = new Mydatabase();
$db = $database2->open_connection2();
//print_r($db);


 $query1 = "SELECT * FROM users";
 $result = $db->query($query1);

$query2 = "SELECT
                id, username, password, first_name, last_name
            FROM
                users";

$stmt = $db->query($query2);
 

if($result->num_rows>0){
 
    // users array
    $users_arr=array();
    $users_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = mysqli_fetch_array($stmt)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $user_item=array(
            "id" => $id,
            "username" => $username,
            "password" => $password,
            "first_name" => $first_name,
        );
 
        array_push($users_arr["records"], $user_item);
    }
 
    echo json_encode($users_arr);
}
 
else{
    echo json_encode(
        array("message" => "No users found.")
    );
}

?>