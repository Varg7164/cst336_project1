<?php

include '../../includes/dbConnection.php';
$dbConn = getDatabaseConnection('sportsStore');
function getClothing(){
    global $dbConn;
    $sql = "SELECT DISTINCT(clothesType) FROM `clothing`";
    $statement=$dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchALL();  
    
    foreach($records as $record) {
        echo "<option value='" . $record['clothesType'] . "'>" . $record['clothesType'] . "</option>";
    }
    
}

function getEquipment(){
    
}

function getSports(){
    global $dbConn;
    $sql = "SELECT * FROM `Sports`";
    $statement=$dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchALL();  
    foreach($records as $record) {
        echo "<option value='" . $record['sportName'] . "'>" . $record['sportName'] . "</option>";
    }
    
}

function search(){
    global $dbConn;
    $sql = "SELECT * 
            FROM device 
            WHERE 1 " ;  //Getting all records 
            
            if (!empty($_GET['clothesType'])){
                //type has been selected
                $sql = $sql . " AND clothesType = :clothesType";
                $namedParameters[':clothesType'] = $_GET['clothesType'];
            }
            
            else if (!empty($_GET['sportName'])){
                //type has been selected
                $sql = $sql . " AND sportName = :sportName";
                $namedParameters[':sportName'] = $_GET['sportName'];
            }
            
            $statement= $dbConn->prepare($sql); 
            $statement->execute($namedParameters); //Always pass the named parameters, if any
            $records = $statement->fetchALL(PDO::FETCH_ASSOC);  
            
            foreach($records as $record) {
              echo"<ul>";
              echo "<li> <input type='checkbox' name='cart[]'    value =" . $record['clothesId'] . ">";
              echo  $record['clothesName'] . " - ". $record['clothesType'] .  " - ". $record['sportName'] . "</li>";
            //   echo "<br/>";
              echo"</ul>";
        
      }
            
    
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
            <select>
                <option value ="default">Select One</option>
                <?=getClothing()?>
            </select>
            Sports:
            <select>
                <option value ="default">Select One</option>
                <?=getSports()?>
            </select>
            <input type="submit" name ="submit" value="Search"/>
        </form>

    </body>
</html>