<?php 
  // error_reporting(0);
  require "../config/db.php";
  header('Content-Type: application/json');
  
  $resObj = new stdClass();
  $resObj->success = false;
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    try {
      $data = file_get_contents('php://input');
      $data = json_decode($data, true);
      // var_dump($data);
      $sql = "SELECT * FROM medicine WHERE  name LIKE '".$data['token']."%';";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result) > 0){
        $resObj->success = true;
        $resObj->data = array();
        while($row = mysqli_fetch_assoc($result)){
          array_push($resObj->data, $row);
        }
      }
      else {
        $resObj->success = false;
        $resObj->message = "No medicine found";
      }
    }
    catch(Exception $e){
      $resObj->success = false;
      $resObj->message = $e->getMessage();
    }
  }
  else {
    $resObj->success = false;
    $resObj->message = "No POST data";
  }

  $resJSON = json_encode($resObj);
  echo $resJSON;
?>