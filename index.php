<?php

session_start();

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
    if(isset($_GET['filter'])){
        if (!empty($_GET['clothesType'])){
            // echo $_GET['itemType'];
            $sql = $sql . " WHERE clothesType = \"" . $_GET['clothesType'] . "\"";
            
            if (!empty($_GET['sportsType'])){
                // echo $_GET['sportsType'];
                $sql = $sql . " AND sportName = \"" . $_GET['sportsType'] . "\"";
            }
            
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
              echo "<li> <input type='checkbox' name='cart[]'    value =" . $record['clothesName'] . ">";
              echo  "<a target='_blank' href=\"". $record['link'] . "\"" . ">" . $record['clothesName'] . "</a>" . "</li>";
            //   echo "<br/>";
              echo"</ul>";
            }

}

function searchEquipBalls(){
    global $dbConn;
    $sql = "SELECT * FROM `equipment` e INNER JOIN `Sports` s 
            ON e.sportId = s.sportId";  //Getting all records 
    
    if(isset($_GET['filter'])){        
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
    }
    
    $statement= $dbConn->prepare($sql); 
    $statement->execute(); //Always pass the named parameters, if any
    $records = $statement->fetchALL(PDO::FETCH_ASSOC);  
    
    foreach($records as $record) {
              echo"<ul style>";
              echo "<li> <input type='checkbox' name='cart[]'    value =" . $record['equipName'] . ">";
              echo  "<a target='_blank' href=\"". $record['link'] . "\"" . ">" . $record['equipName'] . "</a>" . "</li>";
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
        
        <h1>Sporting Goods</h1>
        
        <table>
            <form>
                <tr>
                    <th>Clothes</th></br>
                    <th>Equipment</th>
                    <th>Sports</th>
                    <th>Search</th>
                    <th>Check Out</th>
                </tr>
                <tr>
                    <td>
                        Article Type:
                        <select name ="clothesType">
                             <option value ="default">Select One</option>
                             <?= $records = getClothing();
                                 foreach($records as $record) {
                                 echo "<option value='" . $record['clothesType'] . "'>" . $record['clothesType'] . "</option>";
                                 }
                             ?>
                        </select>
                    </td>
                    
                    <td>
                        Equipment:
                        <select name ="itemType">
                             <option value ="default">Select One</option>
                             <option value ="balls">balls</option>
                             <option value ="equipment">equipment</option>
                        </select>
                    </td>
                    
                    <td>
                        Sport:
                        <select name = "sportsType">
                            <option value ="default">Select One</option>
                            <?= 
                                 $records = getSports();
                                 foreach($records as $record) {
                                     echo "<option value='" . $record['sportName'] . "'>" . $record['sportName'] . "</option>";
                                     }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="submit" name="filter" value="Filter"/>
                    </td>
                    
                    </form>
                    
                    <td>
                        <form action="displayCart.php">
                            <input type="submit" name="checkOut" value="Check Out"/>
                        
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
                </form>
        </table>
        
    </body>
    
    <footer>
        <a  target='_blank' href="https://trello.com/b/Xiwk4wR5/cst-336-project-1">Trello Page</a>
        </br>
        <a target='_blank' href="https://drive.google.com/a/csumb.edu/file/d/0Byh8lROKlWbnbXJHSXVTT2l0R28/view?usp=sharing">Group Google Doc</a>
    </footer>
</html>