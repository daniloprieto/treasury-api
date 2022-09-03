<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require('./connect.php');

$JSONData = file_get_contents("php://input");
$data = json_decode($JSONData);

$id = isset($data->id) ? $data->id : null;
$status = isset($data->status) ? $data->status : 3;
$name = isset($data->name) ? mysqli_real_escape_string($connect, trim($data->name)) : null;
$lastName = isset($data->lastName) ? mysqli_real_escape_string($connect, trim($data->lastName)) : null;
$amount = isset($data->amount) ? $data->amount : 'DEFAULT';
$type = isset($data->type) ? mysqli_real_escape_string($connect, trim($data->type)) : null;
$digital = isset($data->digital) ? mysqli_real_escape_string($connect, trim($data->digital)) : null;
$description = isset($data->description) ? mysqli_real_escape_string($connect, trim($data->description)) : null;
$userId = isset($data->treasurer) ? mysqli_real_escape_string($connect, trim($data->treasurer)) : null;

$sql ="
UPDATE tickets SET `userId`=$userId,`status`=$status WHERE id=$id
";

//$create = mysqli_query($connect, $sql);
  
if (mysqli_query($connect, $sql)) {
   $id = mysqli_insert_id($connect);
   $newId = json_encode([ "id"=>"$id" ]);
   echo $newId;
   return $newId;
} else {
   $error=["error"=>"Not recorded"];
   echo $error;
   return $error;
}

?>