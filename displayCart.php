<?php

include '../../includes/dbConnection.php';
$dbConn = getDatabaseConnection('sportsStore');


session_start(); //You must always use this line to start or resume a session
//session_destroy();

if (!isset($_SESSION['cart'])) {
     $_SESSION['cart'] = array();  //initializing session variable
  }

$cart = $_GET['cart'];

foreach($cart as $element){
    if(!in_array($element, $_SESSION['cart'])){//avoid duplicate device values
        $_SESSION['cart'][]=$element;//appending to the array
    }
}


echo "Items in Cart: <br/>";
foreach($_SESSION['cart'] as $element){
    echo $element . "<br/>";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title> </title>
    </head>
    <body>

    </body>
</html>