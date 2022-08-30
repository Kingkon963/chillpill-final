<?php 
  // error_reporting(0);
  require "../config/db.php";
  include "../classes/Prescription.php";
  include "../classes/PrescriptionItem.php";
  include "../functions/getDoctorName.php";
  header('Content-Type: application/json');
  
  $resObj = new stdClass();
  $resObj->success = false;
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    try {
      $data = file_get_contents('php://input');
      $data = json_decode($data, true);
      // var_dump($data);
      $prescription = new Prescription($data['doctorId'], $data['patientId']);
      $prescription->create();
      if($prescription->created){
        foreach ($data["medicines"] as $medicine){
          $prescriptionItem = new PrescriptionItem($medicine["id"], $medicine['intakeAfter'] , $medicine["atBreakfast"], $medicine["atLunch"], $medicine["atDinner"], $medicine["remarks"], $prescription->id);
        }
        $resObj->success = true;
        $resObj->prescriptionId = $prescription->id;
        $resObj->doctorName = getDoctorName($prescription->doctorId);
      }
      else {
        $resObj->success = false;
        $resObj->message = "Failed to create prescription";
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