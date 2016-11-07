<?php

include '../../includes/dbConnection.php';
$dbConn = getDatabaseConnection('sportsStore');


session_start(); //You must always use this line to start or resume a session
//session_destroy();

if (!isset($_SESSION['cart'])) {
     $_SESSION['cart'] = array();  //initializing session variable
  }

$cart = $_GET['cart'];

function displayCart(){
    global $dbConn;
    global $cart;
    
    if(isset($cart)){
        foreach($cart as $element)
        {   
            if ($element[0] == 'C'){
                 $sql = "SELECT * FROM `clothing` WHERE clothesId =\"". $element . "\"";
                 $statement= $dbConn->prepare($sql); 
                 $statement->execute(); //Always pass the named parameters, if any
                 $records = $statement->fetch(); 
         
                 echo $records['clothesName'] . " - " . $records['clothesType'] . "<br/>";
             
            }
    
            else if ($element[0] == 'E'){
                //  echo "EEEEEE";
                 $sql = "SELECT * FROM `equipment` WHERE equipId =\"". $element . "\"";
                 $statement= $dbConn->prepare($sql); 
                 $statement->execute(); //Always pass the named parameters, if any
                 $records = $statement->fetch(); 
         
                 if (strcmp ($records['ball'], "NULL")==0){
                     echo $records['equipName'] . " - " . $records['misc'] . "<br/>";
                     continue;
                 }
         
                 else if (strcmp ($records['misc'],"NULL")==0){
             
                     echo $records['equipName'] . " - " . $records['ball'] . "<br/>";
                     continue;
                 }
            }
    
    // echo $element . "<br/>";
    // if (!in_array($element, $SESSION['cart'])) { //avoid duplicate device Ids
    //   $_SESSION['cart'][] = $element;
    // }
    //    echo $element . "<br/>";
        }
    }
    else
    {
        echo "<h2>The cart is empty.</h3>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Project 1: Display Cart </title>
        
        <link rel="stylesheet" href="css/project1.css" type="text/css" />
    </head>
    <body class="display">
        <h1>Your Shopping Cart</h1>
        <?=displayCart()?>
        
        </br>
        <form action="index.php">
            <input type="submit" name="return" value="Return to Main Page"/>
        </form>
    </body>
</html>