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

function getEquipment(){
    
}

function getSports(){
    global $dbConn;
    $sql = "SELECT * FROM `Sports`";
    $statement=$dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchALL();  

    return $records;
}

function search(){
    
    
    global $dbConn;
    $sql = "SELECT * FROM `clothing` c INNER JOIN `Sports` s 
            ON c.sportId = s.sportId";  //Getting all records 
    
    if (!empty($_GET['clothingType'])){
        // echo $_GET['clothingType'];
        $sql = $sql . " WHERE clothesType = \"" . $_GET['clothingType'] . "\"";
        
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

if (isset($_GET['submit'])){
    //  if (!empty($_GET['sportsType'])){
    //         echo $_GET['sportsType'];
    //  }
    search();  
}


?>


<!DOCTYPE html>
<html>
    <head>
        <title> </title>
    </head>
    <body>
        
        <form>
            Clothes:
            <select name ="clothingType">
                <option value ="default">Select One</option>
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

    </body>
</html>