<?php 
require '../config/db.php';
  session_start();
  if(isset($_SESSION['loggedin'])){
    echo "<script>console.log('". $_SESSION["loggedin"] ."')</script>";
  }
  else{
    echo "<script>window.location.href='/chillpill/'</script>";
  }

  $sql = "SELECT * FROM `prescription`
    JOIN `patient`
    ON `prescription`.`issuedFor` = `patient`.`phone`
    WHERE `issuedBy` = '". $_SESSION['doctorId'] ."';";
  $result = mysqli_query($conn, $sql);

  $get_today_count_sql = "SELECT * FROM `prescription` WHERE `issuedBy` = '". $_SESSION['doctorId'] ."' AND DATE(`createdAt`) =  DATE(CURRENT_DATE);";
  $get_today_count_result = mysqli_query($conn, $get_today_count_sql);

?>
<!DOCTYPE html>
<html lang="en" data-theme="emerald">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/chillpill/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <script type="module" crossorigin src="/chillpill/assets/modulepreload-polyfill.js"></script>
    <link rel="stylesheet" href="/chillpill/assets/index.css">
  </head>
  <body class="h-screen">
    <div class="navbar bg-base-100 sticky top-0">
      <div>
        <a class="btn btn-ghost normal-case text-xl">ChillPill</a>
      </div>
      <div class="flex-1">
        <label for="prescribe-modal" class="btn btn-primary text-primary-content">Prescribe</label>
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
            <li><a href="../logout/">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container">
      <section class="mb-5 flex gap-5">
        <div class="stats shadow">
          <div class="stat">
            <div class="stat-title">Total Prescriptions</div>
            <div class="stat-value"><?php echo mysqli_num_rows($result) ?></div>
            <div class="stat-desc">All time</div>
          </div>
        </div>
        <div class="stats shadow">
          <div class="stat">
            <div class="stat-title">Today's Prescriptions</div>
            <div class="stat-value"><?php echo mysqli_num_rows($get_today_count_result) ?></div>
            <div class="stat-desc">21% more than previous day</div>
          </div>
        </div>
      </section>

      <!-- Recent patients -->
      <section>
        <div class="heading">
          <h1 class="heading__text">Recent Prescriptions</h1>
          <hr class="heading__underline" />
        </div>
        <div class="overflow-x-auto max-h-[50vh]">
          <table class="table w-full">
            <thead>
              <tr>
                <th class="bg-base-300">#</th>
                <th class="bg-base-300">Name</th>
                <th class="bg-base-300">Phone</th>
                <th class="bg-base-300">Date Time</th>
                <th class="bg-base-300">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 1;
                while($row = mysqli_fetch_assoc($result)){
                  echo "                                
                  <tr>
                    <th>$i</th>
                    <td>". $row['name'] ."</td>
                    <td>". $row['phone'] ."</td>
                    <td>
                      ". $row['createdAt'] ."
                    </td>
                    <td>
                      <a class='btn btn-ghost' href='/chillpill/records/?phone=".$row['phone']."'>Details</a>
                    </td>
                  </tr>";
                  $i++;
                } 
              ?>
              <!-- <tr>
                <th>2</th>
                <td>Hart Hagerty</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>3</th>
                <td>Brice Swyre</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>4</th>
                <td>Marjy Ferencz</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>5</th>
                <td>Yancy Tear</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>6</th>
                <td>Irma Vasilik</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>7</th>
                <td>Meghann Durtnal</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>8</th>
                <td>Sammy Seston</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>9</th>
                <td>Lesya Tinham</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>10</th>
                <td>Zaneta Tewkesbury</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>11</th>
                <td>Andy Tipple</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>12</th>
                <td>Sophi Biles</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>13</th>
                <td>Florida Garces</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr>
              <tr>
                <th>14</th>
                <td>Maribeth Popping</td>
                <td>01512365487</td>
                <td>13/08/2022 @17:17</td>
                <td>
                  <button class="btn btn-ghost">Details</button>
                </td>
              </tr> -->
            </tbody>
            <!-- <tfoot>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Job</th>
                <th>company</th>
                <th>location</th>
                <th>Last Login</th>
                <th>Favorite Color</th>
              </tr>
            </tfoot> -->
          </table>
        </div>
      </section>
    </div>

    <!--Prescribe Modal -->
    <input type="checkbox" id="prescribe-modal" class="modal-toggle" />
    <div class="modal">
      <div class="modal-box" x-data>
        <h3 class="font-bold text-lg">Enter Patien's Phone Number</h3>
        <form action="/prescribe/" @submit.prevent="$store.pd.proceed()" class="form-control my-3">
          <div class="flex items-center w-full">
            <input
              type="tel"
              placeholder="015........"
              class="input w-16 rounded-r-none disabled:bg-base-300"
              value="+88"
              disabled
            />
            <input
              type="text"
              placeholder="015........"
              class="input flex-1 rounded-l-none bg-base-200"
              x-model="$store.pd.phone"
              @input="$store.pd.needToCreatePatient = false"
              maxlength="11"
              required
            />
          </div>
          <label class="label">
            <span class="label-text-alt text-error" x-text="$store.pd.error"></span>
          </label>

          <template x-transition x-if="$store.pd.needToCreatePatient">
            <div>
              <div class="divider">Create Patient's Profile</div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Patient's name</span>
                </label>
                <input
                  class="input input-bordered"
                  type="text"
                  name="name"
                  placeholder="Enter Patient's Name"
                  x-model="$store.pd.patientName"
                  required
                />
              </div>
              <div class="flex gap-5 my-5">
                <div class="form-control">
                  <label for="age" class="label">
                    <span class="label-text">Age</span>
                  </label>
                  <input
                    class="input input-bordered"
                    type="number"
                    name="age"
                    id="age"
                    placeholder="Age"
                    max="200"
                    x-model="$store.pd.patientAge"
                    @input="v => v.pa"
                    required
                  />
                </div>
                <div class="form-control">
                  <label for="gender" class="label">
                    <span class="label-text">Gender</span>
                  </label>
                  <select
                    x-model="$store.pd.patientGender"
                    class="select select-bordered"
                    name="gender"
                    id="gender"
                    required
                  >
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                </div>
              </div>
            </div>
          </template>

          <div class="flex justify-end gap-2">
            <div class="modal-action">
              <label for="prescribe-modal" class="btn"> Cancel </label>
            </div>
            <div class="modal-action">
              <input
                class="btn btn-primary"
                type="submit"
                value="Proceed"
                :disabled="$store.pd.phone.length !== 11"
              />
            </div>
          </div>
        </form>
      </div>
    </div>

    <script type="module" src="/chillpill/assets/dashboard.js"></script>

  </body>
</html>
