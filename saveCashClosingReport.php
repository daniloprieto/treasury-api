<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require('./connect.php');

$JSONData = file_get_contents("php://input");
$data = json_decode($JSONData);

$headquarterTreasure = isset($data->headquarterTreasure) ? mysqli_real_escape_string($connect, trim($data->headquarterTreasure)) : 0;
$headquarterTithe = isset($data->headquarterTithe) ? mysqli_real_escape_string($connect, trim($data->headquarterTithe)) : 0;
$headquarterGain = isset($data->headquarterGain) ? mysqli_real_escape_string($connect, trim($data->headquarterGain)) : 0;
$pastorService = isset($data->pastorService) ? mysqli_real_escape_string($connect, trim($data->pastorService)) : 0;
$pastorTithe = isset($data->pastorTithe) ? mysqli_real_escape_string($connect, trim($data->pastorTithe)) : 0;
$pastorGain = isset($data->pastorGain) ? mysqli_real_escape_string($connect, trim($data->pastorGain)) : 0;
$tickets = json_encode($data->tickets);
$userId = isset($data->treasurer) ? mysqli_real_escape_string($connect, trim($data->treasurer)) : null;

$sql="
INSERT INTO reports(
    `id`, 
    `headquarter`, 
    `headquarterTreasure`, 
    `headquarterTithe`, 
    `headquarterGain`, 
    `pastorService`, 
    `pastorTithe`, 
    `pastorGain`, 
    `treasurer`, 
    `createdAt`, 
    `updatedAt`, 
    `status`, 
    `tickets`) VALUES (
        DEFAULT,
        (SELECT U.headquarter FROM users U WHERE id=$userId),
        $headquarterTreasure,
        $headquarterTithe,
        $headquarterGain,
        $pastorService,
        $pastorTithe,
        $pastorGain,
        $userId,
        DEFAULT,
        DEFAULT,
        1,
        '$tickets')
";

//$create = mysqli_query($connect, $sql);
  
if (mysqli_query($connect, $sql)) {
   $id = mysqli_insert_id($connect);
   $newId = json_encode([ "id"=>"$id" ]);
   echo $newId;
   return $newId;
} else {
   $error=["error"=>"Not recorded report"];
   echo json_encode($error);
   return json_encode($error);
}

?>