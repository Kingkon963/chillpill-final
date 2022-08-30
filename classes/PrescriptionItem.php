<?php 
class PrescriptionItem {
  public $id;
  public $medicineId;
  public $timeToTake;
  public $takenAtBreakfast;
  public $takenAtLunch;
  public $takenAtDinner;
  public $remarks;
  public $prescriptionId;

  public $created = false;

  function __construct($medicineId, $timeToTake, $takenAtBreakfast, $takenAtLunch, $takenAtDinner, $remarks, $prescriptionId) {
    $this->medicineId = $medicineId;
    $this->timeToTake = $timeToTake === "1" ? 'after' : 'before';
    $this->takenAtBreakfast = $takenAtBreakfast ? 1 : 0;
    $this->takenAtLunch = $takenAtLunch ? 1 : 0;
    $this->takenAtDinner = $takenAtDinner ? 1 : 0;
    $this->remarks = $remarks;
    $this->prescriptionId = $prescriptionId;

    require "../config/db.php";

    $sql = "INSERT INTO `prescriptionitem`(`medicine`, `timeToTake`, `takenAtBreakfast`, `takenAtLunch`, `takenAtDinner`, `remarks`, `prescriptionId`) VALUES 
    ('$this->medicineId','$this->timeToTake','$this->takenAtBreakfast','$this->takenAtLunch','$this->takenAtDinner','$this->remarks','$this->prescriptionId')";
    $result = mysqli_query($conn, $sql);
    if($result){
      $getId_sql = "SELECT LAST_INSERT_ID();";
      $getId_result = mysqli_query($conn, $getId_sql);
      if(mysqli_num_rows($getId_result) > 0){
        $row = mysqli_fetch_assoc($getId_result);
        $this->id = $row['LAST_INSERT_ID()'];
        $this->created = true;
      }
    }
    else {
      die("Failed to create prescription item");
    }
  }

}
?>