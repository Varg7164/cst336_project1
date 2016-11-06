<?php

session_start();

if (!empty($_GET['clothesType'])){
        $test = $_GET['clothesType'];
        echo $test;
    }

include '../../includes/dbConnection.php';
$dbConn = getDatabaseConnection('sportsStore');
function getClothing(){
    global $dbConn;
    $sql = "SELECT DISTINCT(clothesType) FROM `clothing`";
    $statement=$dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchALL();  
    
    return $records;
            
    
}

// function getEquipment(){
//      global $dbConn;
//     $sql = "SELECT DISTINCT(clothesType) FROM `clothing`";
//     $statement=$dbConn->prepare($sql);
//     $statement->execute();
//     $records = $statement->fetchALL();  
    
//     return $records;
    
// }

function getSports(){
    global $dbConn;
    $sql = "SELECT * FROM `Sports`";
    $statement=$dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchALL();  

    return $records;
}

function searchClothes(){
    
    
    global $dbConn;
    $sql = "SELECT * FROM `clothing` c INNER JOIN `Sports` s 
            ON c.sportId = s.sportId";  //Getting all records 
    
    if (!empty($_GET['itemType'])){
        // echo $_GET['itemType'];
        $sql = $sql . " WHERE clothesType = \"" . $_GET['itemType'] . "\"";
        
        if (!empty($_GET['sportsType'])){
            // echo $_GET['sportsType'];
            $sql = $sql . " AND sportName = \"" . $_GET['sportsType'] . "\"";
        }
    }
    
    
    // if (isset($_GET['clothesType'])){
    //     $test = $_GET['clothesType'];
    //     echo $test;
    // }
    
    $statement= $dbConn->prepare($sql); 
    $statement->execute(); //Always pass the named parameters, if any
    $records = $statement->fetchALL(PDO::FETCH_ASSOC);  
            
            foreach($records as $record) {
              echo"<ul>";
              echo "<li> <input type='checkbox' name='cart[]'    value =" . $record['clothesId'] . ">";
              echo  "<a href=\"". $record['link'] . "\"" . ">" . $record['clothesName'] . "</a>" . " - ". $record['sportName']. "</li>";
            //   echo "<br/>";
              echo"</ul>";
            }

}

function searchEquipBalls(){
    global $dbConn;
    $sql = "SELECT * FROM `equipment` e INNER JOIN `Sports` s 
            ON e.sportId = s.sportId";  //Getting all records 
            
    if (!empty($_GET['itemType'])){
        
        // echo $_GET['itemType'];
        
        if ($_GET['itemType'] == "balls"){
             if ($_GET['sportsType']=="Baseketball"){
                $_GET['sportsType']="Basketball"; //Fixing spelling error
            }
            
            if ($_GET['sportsType']=="Soccer"){
                $_GET['sportsType']="Soccer Ball"; //Fixing spelling error
            }
        
            if (!empty($_GET['sportsType'])){
                // echo $_GET['sportsType'];
                $sql = $sql . " WHERE ball= \"" . $_GET['sportsType'] . "\"";
            }
        }
        
        else if ($_GET['itemType'] ==  "equipment"){
            // echo "TEST";
            $sql = $sql . " WHERE misc != 'NULL' AND sportName = \"" . $_GET['sportsType'] . "\"";
        }
        
    }
    
    $statement= $dbConn->prepare($sql); 
    $statement->execute(); //Always pass the named parameters, if any
    $records = $statement->fetchALL(PDO::FETCH_ASSOC);  
    
    foreach($records as $record) {
              echo"<ul style>";
              echo "<li> <input type='checkbox' name='cart[]'    value =" . $record['equipId'] . ">";
              echo  "<a href=\"". $record['link'] . "\"" . ">" . $record['equipName'] . "</a>" . " - ". $record['sportName']. "</li>";
            //   echo "<br/>";
              echo"</ul>";
    }
    
    
    
}

function goPlace(){
    if (isset($_GET['submit'])){
     if (!empty($_GET['itemType'])){
         
        //  echo $_GET['itemType'];
        if (strcmp($_GET['itemType'], "balls")==0 || strcmp($_GET['itemType'], "equipment")==0){
            searchEquipBalls();
        }
        else {
            searchClothes();
            
        }
     }
   
    }
    else{
        echo "null";
    }
}




?>


<!DOCTYPE html>
<html>
    <head>
        <title> </title>
        <link rel="stylesheet" href="../css/style.css" type="text/css">
    </head>
    <body>
        
        <form>
            Items:
            <select name ="itemType">
                <option value ="default">Select One</option>
                <option value ="balls">balls</option>
                <option value ="equipment">equipment</option>
                <?= $records = getClothing();
                    foreach($records as $record) {
                        echo "<option value='" . $record['clothesType'] . "'>" . $record['clothesType'] . "</option>";
                    }
                ?>
                
                
            </select>
            Sports:
            <select name = "sportsType">
                <option value ="default">Select One</option>
                <?= 
                    $records = getSports();
                    foreach($records as $record) {
                        echo "<option value='" . $record['sportName'] . "'>" . $record['sportName'] . "</option>";
                    }
                
                ?>
            </select>
            <input type="submit" name ="submit" value="Search"/>
        </form>
        
        <form action="displayCart.php">
             <?=goPlace()?>
           <br />
           <input type="submit" value="Continue">
         </form>
       

    </body>
</html>