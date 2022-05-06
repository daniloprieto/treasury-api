<?php
require('./connect.php');

$data = json_decode(file_get_contents("php://input"));

$name = isset($data->name) ? mysqli_real_escape_string($connect, trim($data->name)) : null;
$lastName = isset($data->lastName) ? mysqli_real_escape_string($connect, trim($data->lastName)) : null;
$diezmo = isset($data->diezmo) ? $data->diezmo : 'DEFAULT';
$ofrenda = isset($data->ofrenda) ? $data->ofrenda : 'DEFAULT';
$date = isset($data->date) ? mysqli_real_escape_string($connect, trim($data->date)) : null;
$user = isset($data->user) ? mysqli_real_escape_string($connect, trim($data->user)) : null;

$insertNewOrder = "INSERT INTO tickets VALUES ('DEFAULT','$name','$lastName', '$diezmo', '$ofrenda','Obera','$date','$user')";

$create = mysqli_query($connect, $insertNewOrder);

?>