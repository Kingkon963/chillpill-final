<?php 
  require "../config/db.php";
  header('Content-Type: application/json');

  $phoneNumber = $_REQUEST["phone"];
  //echo "Phone: " . $phoneNumber . "<br>";

  $sql = "SELECT * FROM `patient` WHERE phone = '$phoneNumber'";

  $result = $conn->query($sql);
  $resObj = new stdClass();
  
  $resObj->userExists = false;

  if($result->num_rows > 0){
    $resObj->userExists = true;
    while($row = $result->fetch_assoc()){
      //echo "Name: " . $row["name"] . "<br>";
      $resObj->name = $row["name"];
    }
  }

  $resJSON = json_encode($resObj);
  echo $resJSON;
?>