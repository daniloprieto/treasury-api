<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require('./connect.php');

$JSONData = file_get_contents("php://input");
$dataObject = json_decode($JSONData);

$n=$dataObject->user;
$p=$dataObject->password;
$d=$_POST["data"];

$USER= isset($n) ? mysqli_real_escape_string($connect, trim($n)) : null;
$PASSWORD= isset($p) ? mysqli_real_escape_string($connect, trim($p)) : null;

$searchUser="
 SELECT 
  U.id, 
  U.name, 
  U.lastName, 
  U.role,
  U.language,
  U.notifications,
  H.name as headquarter  
 FROM users U
 LEFT JOIN headquarters H ON U.headquarter = H.Id
 WHERE U.name='$USER'
 AND U.password='$PASSWORD'
 AND U.status=1";

$users = mysqli_query($connect, $searchUser);

while ($user = mysqli_fetch_array($users))  
  {
    $data = [
     "id" => $user["id"],
     "name" => $user["name"],
     "lastName" => $user["lastName"],
     "headquarter" => $user["headquarter"],
     "language" => $user["language"],
     "notifications" => $user["notifications"],
     "role" => $user["role"]
   ];
  }
  
$json = json_encode($data); // GENERA EL JSON CON LOS DATOS OBTENIDOS
  
header('Content-Type: application/json');

if ($json == 'null') {
  http_response_code(404);
  $json = json_encode([ error => 'Not found']);

} else {
    http_response_code(200);
}

echo $json; // MUESTRA EL JSON GENERADO

return $json;
exit();
?>
