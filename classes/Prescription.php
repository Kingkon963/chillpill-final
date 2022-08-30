<?php 
class Prescription {
  public $id;
  public $doctorId;
  public $patientId;

  public $created = false;

  function __construct($doctorId, $patientId) {
    $this->doctorId = $doctorId;
    $this->patientId = $patientId;
  }
  
  function create() {
    require "../config/db.php";
    $sql = "INSERT INTO `prescription`(`issuedBy`, `issuedFor`) VALUES ('$this->doctorId','$this->patientId')";
    $result = mysqli_query($conn, $sql);
    if($result){
      //var_dump($result);
      $get_id_sql = "SELECT LAST_INSERT_ID();";
      $get_id_result = mysqli_query($conn, $get_id_sql);
      if(mysqli_num_rows($get_id_result) > 0){
        $row = mysqli_fetch_assoc($get_id_result);
        $this->id = $row['LAST_INSERT_ID()'];
        // var_dump($this->id);
        $this->created = true;
      }
      $this->created = true;
    }
    else {
      die("Failed to create prescription");
    }
  }
  
}
?>