<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require('./connect.php');

$JSONData = file_get_contents("php://input");
$dataObject = json_decode($JSONData);

$date = isset($dataObject->date) ? mysqli_real_escape_string($connect, trim($dataObject->date)) : null;

//$searchTickets="SELECT * FROM tickets WHERE date='$date'";
$searchTickets="
SELECT 
 T.id, 
 T.name, 
 T.lastName,
 T.amount,
 T.type,
 T.digital,
 T.description,
 T.status, 
 T.createdAt, 
 T.updatedAt, 
 H.name as headquarter,  
 H.country as country,
 U.lastName as treasurer 
FROM tickets T
LEFT JOIN headquarters H 
ON T.headquarter = H.Id
LEFT JOIN users U ON T.userId = U.Id
WHERE T.createdAt >= DATE '$date'
ORDER BY id DESC";

$tickets = mysqli_query($connect, $searchTickets);

while ($ticket = mysqli_fetch_array($tickets))  
  {
    $data[] = [
      "id"=>$ticket["id"],
      "name"=>$ticket["name"], 
      "lastName"=>$ticket["lastName"],
      "amount"=>$ticket["amount"], 
      "type"=>$ticket["type"],
      "digital"=>$ticket["digital"], 
      "status"=>$ticket["status"], 
      "createdAt"=>$ticket["createdAt"],
      "updatedAt"=>$ticket["updatedAt"],   
      "headquarter"=>$ticket["headquarter"],   
      "country"=>$ticket["country"],
      "treasurer"=>$ticket["treasurer"]
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