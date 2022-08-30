<?php 

class Patient {
  public $phone;
  public $email;
  public $name;
  public $age;
  public $gender;

  public $found = false;

  function __construct($phone)
  {
    require '../config/db.php';

    $sql = "SELECT * FROM patient WHERE phone = '$phone'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
      $this->found = true;
      $row = mysqli_fetch_assoc($result);
      // var_dump($row);
      $this->phone = $row['phone'];
      $this->email = $row['email'];
      $this->name = $row['name'];
      $this->age = $row['age'];
      $this->gender = $row['gender'];
      // var_dump($this);
    }
    else {
      $this->found = false;
    }
  }
}
?>