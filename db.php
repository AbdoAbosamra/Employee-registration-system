<?php 
try{
    $conn = new PDO("mysql:host=localhost;dbname=ers", 'root', '07775000' );   
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND,"SET NAMES UTF8");
    
}catch(PDOException $e){
    echo "The Error is: ". $e->getMessage();
}