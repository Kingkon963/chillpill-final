<?php 
require "../config/db.php";
function getDoctorName($id) {
  $sql = "SELECT name FROM doctor WHERE phone = $id";
  $result = mysqli_query($GLOBALS['conn'], $sql);
  $row = mysqli_fetch_assoc($result);
  if(mysqli_num_rows($result) > 0){
    return $row['name'];
  }
  else {
    return "";
  }
}

?>
