<?php

include '../../includes/dbConnection.php';
$dbConn = getDatabaseConnection('sportsStore');

session_start();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array(); //initializing the session variable
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
    <footer>
        <hr> &copy; Michael Vargas, Scott Ligon, Tristan Anderson, 2016. Disclaimer: The information on this page might not be acurate. It's used for academic puposes.
        <br />
        <img src="../../img/csumb-logo.png" alt="CSUMB Logo"/>
        <br />
        <a  target='_blank' href="https://trello.com/b/Xiwk4wR5/cst-336-project-1">Trello Page</a>
        </br>
        <a target='_blank' href="https://drive.google.com/a/csumb.edu/file/d/0Byh8lROKlWbnbXJHSXVTT2l0R28/view?usp=sharing">Group Google Doc</a>
    </footer>
</html>