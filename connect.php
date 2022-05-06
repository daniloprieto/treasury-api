<?php
require_once('./config.php');

$connect = new mysqli($server, $username, $password, $db);

if(mysqli_connect_errno()){
    echo 'no conectado', mysqli_connect_error();
    exit();
}else{
    //para que reciba ñ o tildes
    $connect->set_charset("utf8");
}
?>