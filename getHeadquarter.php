<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require('./connect.php');

$JSONData = file_get_contents("php://input");
$dataObject = json_decode($JSONData);

$headquarter = isset($dataObject->headquarter) ? mysqli_real_escape_string($connect, trim($dataObject->headquarter)) : null;

$searchHeadquarter="SELECT * FROM headquarters H WHERE H.name='$headquarter'";

$hqs = mysqli_query($connect, $searchHeadquarter);

while ($hq = mysqli_fetch_array($hqs))  
  {
    $data[] = [
      "id"=>$hq["id"],
      "name"=>$hq["name"], 
      "country"=>$hq["country"]
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