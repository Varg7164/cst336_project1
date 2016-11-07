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
              echo  "<a target='_blank' href=\"". $record['link'] . "\"" . ">" . $record['clothesName'] . "</a>" . " - ". $record['sportName']. "</li>";
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
              echo  "<a target='_blank' href=\"". $record['link'] . "\"" . ">" . $record['equipName'] . "</a>" . " - ". $record['sportName']. "</li>";
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
        <title>Project 1: Sports Store</title>
        
         <link rel="stylesheet" href="css/project1.css" type="text/css" />
        
    </head>
    <body>
        
        
        <table>
            <form>
                <tr>
                    <th>Clothes</th></br>
                    <th>Equipment</th>
                    <th>Check Out</th>
                </tr>
                <tr>
                    <td>
                        By Type:
                        <select name="cType">
                        <option value ="default">Select One</option>
                            <?= $records = getClothing();
                                foreach($records as $record) {
                                 echo "<option value='" . $record['clothesType'] . "'>" . $record['clothesType'] . "</option>";
                                }
                            ?>
                        </select>
                        </br>
                        By Brand: 
                        <input type="text" name="message" size="16" maxlength="16" placeholder="Search by brand"/>
                        
                        </br>
                        
                        View By:
                        <input type="radio" name="asc" value="asc" id="ascCell"/> <label for="ascCell"> Ascending </label>
                        <input type="radio" name="desc" value="desc" id="descCell"/> <label for="descCell"> Descending </label>

                        </br>
                        
                        By Sport:
                        <select name="sType">
                            <option value ="default">Select One</option>
                            <?= 
                                $records = getSports();
                                foreach($records as $record) {
                                    echo "<option value='" . $record['sportName'] . "'>" . $record['sportName'] . "</option>";
                                }
                            ?>
                        </select>
                        
                        </br>
                        
                        <input type="submit" name="filterC" value="Filter"/>
                    </td>
                    
                    <td>
                        <input type="radio" name="balls" value="balls" id="ballCell"/> <label for="ballCell"> Balls </label>
                        <input type="radio" name="equipment" value="equip" id="equipCell"/> <label for="equipCell"> Equipment </label>
                        
                        </br>
                                                
                        View By:
                        <input type="radio" name="asc" value="asc" id="ascCell"/> <label for="ascCell"> Ascending </label>
                        <input type="radio" name="desc" value="desc" id="descCell"/> <label for="descCell"> Descending </label>

                        </br>
                        
                        By Sport:
                        <select name="spType">
                            <option value ="default">Select One</option>
                            <?= 
                                $records = getSports();
                                foreach($records as $record) {
                                    echo "<option value='" . $record['sportName'] . "'>" . $record['sportName'] . "</option>";
                                }
                            ?>
                        </select>
                        
                        </br>
                        
                        <input type="submit" name="filterEquip" value="Filter"/>
                    </td>
                    
                    </form>
                    
                    <td>
                        <form action="displayCart.php">
                            <input type="submit" name="checkOut" value="Check Out"/>
                        </form>
                    </td>
                    
                </tr>
                
                <tr>
                    <td>
                        <?=searchClothes()?>
                    </td>
                    
                    <td>
                        <?=searchEquipBalls()?>
                    </td>
                </tr>
        </table>
    </body>
</html>