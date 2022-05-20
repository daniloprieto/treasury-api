<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require('./connect.php');

$JSONData = file_get_contents("php://input");
$dataObject = json_decode($JSONData);

$date = isset($dataObject->date) ? mysqli_real_escape_string($connect, trim($dataObject->date)) : null;

$searchReports="
SELECT 
 R.id, 
 R.headquarterTreasure, 
 R.headquarterTithe,
 R.headquarterGain,
 R.pastorService,
 R.pastorTithe,
 R.pastorGain,
 R.createdAt, 
 R.updatedAt,
 R.status,
 R.tickets, 
 H.name as headquarter,
 U.lastName as treasurer 
FROM reports R
LEFT JOIN headquarters H 
ON R.headquarter = H.Id
LEFT JOIN users U ON R.userId = U.Id
WHERE R.createdAt >= DATE '$date'
ORDER BY id DESC";

$s="SELECT 
 R.id,
 R.headquarterTreasure,
 R.headquarterTithe,
 R.headquarterGain,
 R.pastorService,
 R.pastorTithe,
 R.pastorGain,
 R.createdAt, 
 R.updatedAt,
 R.status,
 R.tickets,
 H.name as headquarter,
 U.lastName as treasurer
FROM reports R
LEFT JOIN headquarters H 
ON R.headquarter = H.Id
LEFT JOIN users U 
ON R.treasurer = U.Id
";

$reports = mysqli_query($connect, $s);

while ($report = mysqli_fetch_array($reports))  
  {
    $data[] = [
      "id"=>$report["id"],
      "headquarter"=>$report["headquarter"], 
      "headquarterTreasure"=>$report["headquarterTreasure"],
      "headquarterTithe"=>$report["headquarterTithe"], 
      "headquarterGain"=>$report["headquarterGain"],
      "pastorService"=>$report["pastorService"], 
      "pastorTithe"=>$report["pastorTithe"],
      "pastorGain"=>$report["pastorGain"],   
      "createdAt"=>$report["createdAt"],
      "updatedAt"=>$report["updatedAt"],   
      "status"=>$report["status"],
      "treasurer"=>$report["treasurer"],
      "tickets"=>$report["tickets"]
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