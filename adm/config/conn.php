<?php

#$host= "mysql2.nmatec.com.br";
#$user= "nmatec14";
#$password="Js5QLMMUt9b9@EB";
$host= "localhost";
$user= "root";
$password="";
$database="project_administrative_system";
$charset = "utf8";

try{
    $conn = new PDO("mysql:host={$host};dbname={$database};charset={$charset};", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e){
    return false;
}
