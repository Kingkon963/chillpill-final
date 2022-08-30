<?php 
  $session_started = session_start();
  require '../config/db.php';
  if(!isset($_REQUEST["phone"])){
    die("No phone number");
  }

  $sql = "SELECT `prescriptionId`, `createdAt`, `name` FROM `prescription`
  JOIN `doctor`
  ON `prescription`.`issuedBy` = `doctor`.`phone`
  WHERE `prescription`.`issuedFor` = '" . $_REQUEST["phone"] . "'"
  ."ORDER BY `prescription`.`createdAt` DESC;";
  
  global $result, $resultRow, $drawerSide, $drawerContent, $prescriptionItems;
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result) === 0){
    die("Query Failed" . mysqli_error($conn));
  }




  $indx = 0;
  $drawerSide = "";
  $drawerContent = "";
  $prescriptionItems = "";
  while($row = mysqli_fetch_assoc($result)) {

    $items_sql = "SELECT * FROM `prescriptionitem`
    JOIN `medicine`
    ON `prescriptionitem`.`medicine` = `medicine`.`medicineId`
    WHERE `prescriptionitem`.`prescriptionId` = ". $row["prescriptionId"] .";";

    $items_result = mysqli_query($conn, $items_sql);
    if(mysqli_num_rows($items_result) === 0){
      // die("Items Query Failed" . mysqli_error($conn));
    }
    while($items_row = mysqli_fetch_assoc($items_result)) {
      $prescriptionItems .= "<li>
      <span>". $items_row["name"] ."</span> - (<span>". $items_row["takenAtBreakfast"] ."</span> - <span>". $items_row["takenAtLunch"] ."</span> - <span>". $items_row["takenAtDinner"] ."</span>) -
      <span>". $items_row["timeToTake"] ."</span> meal - <span>". $items_row["remarks"] ."</span>
    </li>";
    }

   $drawerSide .= "<li @click='activeIndx = $indx'>
      <div
        class='flex flex-col items-start gap-0' :class=\"activeIndx === " . $indx . " ? 'active' : ''\"
      >
        <div class='w-full flex justify-between'>
          <span>" . $row['createdAt'] . "</span>
        </div>
      </div>
    </li>";

    $drawerContent .= "<template x-teleport='#prescriptionContainer'>
    <div x-show='activeIndx === $indx'>
      <div class='flex flex-col flex-wrap gap-0 max-w-xs'>
        <!-- <span class='text-gray-600'>Doctor:</span> -->
        <span class='font-bold'>". $row['name'] ."</span>
        <span class='text-sm'>Retina Specialist</span>
        <span class='text-xs text-gray-400'>MBBS, FCPS</span>
      </div>
      <div class='divider'></div>
      <div>
        <ol class='list-decimal p-10'>" . $prescriptionItems . "
        </ol>
      </div>
      <div class='divider'></div>
    </div>
  </template>";
    $indx++;
  }

?>
<!DOCTYPE html>
<html lang="en" data-theme="emerald">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="icon" href="/fav.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>records</title>
    <script type="module" crossorigin src="/chillpill/assets/records.js"></script>
    <link rel="modulepreload" crossorigin href="/chillpill/assets/modulepreload-polyfill.js">
    <link rel="modulepreload" crossorigin href="/chillpill/assets/module.esm.js">
    <link rel="stylesheet" href="/chillpill/assets/index.css">
  </head>
  <body class="records min-h-screen">
    <div class="navbar bg-base-100 sticky top-0 z-50">
      <div>
        <a class="btn btn-ghost normal-case text-xl" href="/chillpill/">ChillPill</a>
      </div>
      <div class="flex-1">
        <a href="/chillpill/dashboard/" class="btn">Dashboard</a>
      </div>
      <div class="flex-none gap-2">
        <div class="form-control">
          <input type="text" placeholder="Search" class="input input-bordered" />
        </div>
        <div class="dropdown dropdown-end">
          <label tabindex="0" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full">
              <img src="https://placeimg.com/80/80/people" />
            </div>
          </label>
          <ul
            tabindex="0"
            class="mt-3 p-2 shadow menu menu-compact dropdown-content bg-base-100 rounded-box w-52"
          >
            <li>
              <a class="justify-between">
                Profile
                <span class="badge">New</span>
              </a>
            </li>
            <li><a>Settings</a></li>
            <li><a>Logout</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container" x-data="{activeIndx: 0}">
      <?php 
        if(isset($_SESSION['doctorId'])){
          echo "<button class='btn btn-sm' @click='window.history.back()'>Back</button>";
        }
      ?>
      <div class="drawer rounded-lg mt-5">
        <input
          type="checkbox"
          name="drawerToggle"
          id="drawerToggle"
          class="drawer-toggle"
          checked
        />
        <div class="drawer-content h-max rounded-lg p-4 bg-white ml-80">
          <div id="prescriptionContainer"></div>
        </div>
        <div class="drawer-side h-max overflow-y-scroll">
          <!-- <label for="drawerToggle" class="drawer-overlay"></label> -->
          <ul class="menu gap-2 p-4 overflow-y-auto rounded-lg w-80 bg-base-300 text-base-content">
            <?php 
              echo $drawerSide;
            ?>
          </ul>
        </div>
      </div>
      
      <?php
        echo $drawerContent
      ?>
    </div>

    
  </body>
</html>
