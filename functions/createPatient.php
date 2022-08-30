<?php 
  require "../config/db.php";
  header('Content-Type: application/json');
  
  $resObj = new stdClass();
  $resObj->success = false;
  try {
    if(isset($_REQUEST["phone"]) && isset($_REQUEST["name"]) && isset($_REQUEST["age"]) && isset($_REQUEST["gender"])){
      $phone = $_REQUEST["phone"];
      $name = $_REQUEST["name"];
      $age = $_REQUEST["age"];
      $gender = $_REQUEST["gender"];
    
      $sql = "INSERT INTO `patient`(`phone`, `email`, `name`, `age`, `gender`) VALUES ('$phone','','$name','$age','$gender')";
      $result = $conn->query($sql);
      if($result){
        $resObj->success = true;
      }
    }
  }
  catch(Exception $e){
    $resObj->success = false;
    $resObj->message = $e->getMessage();
  }

  $resJSON = json_encode($resObj);
  echo $resJSON;
?>