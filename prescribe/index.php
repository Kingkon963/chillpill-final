<?php require("../config/db.php")?>
<?php 
  $session_started = session_start();
  if(isset($_REQUEST["phone"])){
    $phone = $_REQUEST["phone"];
    include("../classes/Patient.php");
    global $patient, $patientHasRecords;
    $patient = new Patient($phone);
    if(!$patient->found){
      die("No patient found with this number");
    }

    $checkRecordSql = "SELECT * FROM `prescription` WHERE `issuedFor` = '$phone';";
    $checkRecordResult = mysqli_query($conn, $checkRecordSql);
    if(mysqli_num_rows($checkRecordResult) === 0){
      $patientHasRecords = false;
    }else{
      $patientHasRecords = true;
    }

  }
  else {
    die("404 Not Found");
  }
?>
<!DOCTYPE html>
<html lang="en" data-theme="emerald">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="icon" href="/fav.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>prescribe</title>
    <script type="module" crossorigin src="/chillpill/assets/prescribe.js"></script>
    <link rel="modulepreload" crossorigin href="/chillpill/assets/modulepreload-polyfill.js">
    <link rel="modulepreload" crossorigin href="/chillpill/assets/module.esm.js">
    <link rel="stylesheet" href="/chillpill/assets/index.css">
  </head>
  <body class="prescribe min-h-screen">
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

    <div class="container" x-data>
      <div class="divider">Patient Info</div>
      <div class="shadow-md bg-base-300 rounded-md p-10 mb-5">
        <div class="grid grid-cols-3">
          <div class="flex gap-2 items-center">
            <span class="text-gray-600">Name:</span>
            <span class="font-bold"><?php echo $patient->name ?></span>
          </div>
          <div class="flex gap-2 items-center">
            <span class="text-gray-600">Age:</span>
            <span class="font-bold"><?php echo $patient->age ?></span>
          </div>
          <div class="flex gap-2 items-center">
            <span class="text-gray-600">Gender:</span>
            <span class="font-bold"><?php echo $patient->gender ?></span>
          </div>
        </div>
      </div>
      <div class="divider">Prescription</div>
      <div class="shadow-md bg-base-300 rounded-md p-10">
        <div class="flex items-center flex-wrap gap-3">
          <div class="relative flex-1 max-w-sm">
            <input
              class="input w-full"
              type="text"
              name="medicineName"
              id="medicineName"
              placeholder="Medicine Name"
              x-model="$store.prescription.currMed.name"
              @keyup.enter="$store.prescription.addMedicine()"
              @input.debounce="$store.prescription.fetchHints()"
            />
            <div class="search-dropdown" x-show="$store.prescription.openHint">
              <template x-for="hint in $store.prescription.medSuggestions">
                <div
                  class="search-dropdown__item"
                  type="button"
                  @click="$store.prescription.selectMed(hint.medicineId, hint.name)"
                >
                  <p x-text="hint.name">Napa 10mg</p>
                  <!-- <p class="text-[.7em] p-0 text-info-content">Beximco pharma</p> -->
                </div>
              </template>
            </div>
          </div>
          <div>
            <div class="form-control">
              <label class="label cursor-pointer">
                <input
                  type="radio"
                  name="intakeAfterFood"
                  class="radio radio-primary"
                  value="1"
                  x-model="$store.prescription.currMed.intakeAfter"
                />
                <span class="label-text ml-2">after</span>
              </label>
            </div>
          </div>
          <div>
            <div class="form-control">
              <label class="label cursor-pointer">
                <input
                  type="radio"
                  name="intakeAfterFood"
                  class="radio radio-primary"
                  value="0"
                  x-model="$store.prescription.currMed.intakeAfter"
                />
                <span class="label-text ml-2">before</span>
              </label>
            </div>
          </div>
          <div class="divider divider-horizontal border-base-300"></div>
          <div>
            <div class="form-control">
              <label class="label cursor-pointer">
                <input
                  type="checkbox"
                  class="checkbox checkbox-primary"
                  x-model="$store.prescription.currMed.atBreakfast"
                />
                <span class="label-text ml-2">breakfast</span>
              </label>
            </div>
          </div>
          <div>
            <div class="form-control">
              <label class="label cursor-pointer">
                <input
                  type="checkbox"
                  class="checkbox checkbox-primary"
                  x-model="$store.prescription.currMed.atlunch"
                />
                <span class="label-text ml-2">lunch</span>
              </label>
            </div>
          </div>
          <div>
            <div class="form-control">
              <label class="label cursor-pointer">
                <input
                  type="checkbox"
                  class="checkbox checkbox-primary"
                  x-model="$store.prescription.currMed.atDinner"
                />
                <span class="label-text ml-2">dinner</span>
              </label>
            </div>
          </div>
        </div>
        <div class="mt-5 w-full">
          <input
            type="text"
            class="input w-full"
            placeholder="Remarks (optional)"
            x-model="$store.prescription.currMed.remarks"
            @keyup.enter="$store.prescription.addMedicine()"
          />
        </div>
        <button class="btn btn-primary mt-5" @click="$store.prescription.addMedicine()">Add</button>
      </div>
      <div class="mt-5">
        <div class="flex justify-end">
          <?php 
          if($patientHasRecords){
            echo "
            <a class='btn btn-primary' href='/chillpill/records/?phone=$phone'>
              <span class='mr-2'>
                <svg
                  xmlns='http://www.w3.org/2000/svg'
                  class='h-6 w-6'
                  fill='none'
                  viewBox='0 0 24 24'
                  stroke='currentColor'
                  stroke-width='2'
                >
                  <path
                    stroke-linecap='round'
                    stroke-linejoin='round'
                    d='M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
                  />
                </svg>
              </span>
              Previous Records
            </a>";
          }
          ?>
        </div>
        <div class="border border-base-content border-dashed mt-5">
          <ol class="list-decimal bg-base-100 p-10" id="prespriptionPage">
            <template x-for="medicine in $store.prescription.medicines">
              <li class="ml-5 my-2">
                <div class="flex items-start gap-3">
                  <span>
                    <span x-text="medicine.name">Napa 10mg</span> - (<span
                      x-text="medicine.atBreakfast ? 1 : 0"
                    >
                      1 </span
                    >- <span x-text="medicine.atlunch ? 1 : 0">1</span>-
                    <span x-text="medicine.atDinner ? 1 : 0">1</span>) -
                    <span x-text="medicine.intakeAfter == '1' ? 'after' : 'before'">after</span>
                    meal - <span x-text="medicine.remarks">remarks</span>
                  </span>
                  <button
                    @click="$store.prescription.deleteMedicine(medicine.id)"
                    class="tooltip tooltip-right tooltip-error"
                    data-tip="delete"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 hover:stroke-error"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"
                      />
                    </svg>
                  </button>
                </div>
              </li>
            </template>
            <li class="ml-5 my-2">
              <div class="flex items-start gap-3">
                <span>
                  <span x-text="$store.prescription.currMed.name">Napa 10mg</span> - (<span
                    x-text="$store.prescription.currMed.atBreakfast ? 1 : 0"
                  >
                    1 </span
                  >- <span x-text="$store.prescription.currMed.atlunch ? 1 : 0">1</span>-
                  <span x-text="$store.prescription.currMed.atDinner ? 1 : 0">1</span>) -
                  <span x-text="$store.prescription.currMed.intakeAfter == '1' ? 'after' : 'before'"
                    >after</span
                  >
                  meal - <span x-text="$store.prescription.currMed.remarks">remarks</span>
                </span>
                <button
                  @click="$store.prescription.resetCurrMed()"
                  class="tooltip tooltip-right tooltip-error"
                  data-tip="delete"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 hover:stroke-error"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"
                    />
                  </svg>
                </button>
              </div>
            </li>
          </ol>
        </div>
        <div class="flex justify-end mt-5">
          <button
            class="btn btn-secondary"
            :disabled="$store.prescription.medicines.length == 0"
            @click="$store.prescription.send('<?php echo $_SESSION['doctorId'] ?>', '<?php echo $patient->phone ?>')"
          >
            <span class="mr-1">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </span>
            Send
          </button>
        </div>
      </div>
    </div>
    
  </body>
</html>
