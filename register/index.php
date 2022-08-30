<?php require_once "../config/db.php" ?>
<?php 
  $session_started = session_start();
  if(isset($_SESSION["loggedin"])){
    header("Location: /chillpill/");
  }
  $errors = [];
  require_once "../utils/sanitize.php";
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $phone = sanitize_input($_POST["phone"]);
    $email = sanitize_input($_POST["email"]);
    $bmdc = sanitize_input($_POST["bmdc"]);
    $name = sanitize_input($_POST["name"]);
    $pass = md5( sanitize_input($_POST["password"]));
    $confirm_pass = md5( sanitize_input($_POST["confirm_pass"]));
    function validation($phone, $email, $bmdc, $name, $pass, $confirm_pass){
      global $errors;
      $errors = [];
      if(empty($phone)){
        array_push($errors, "Phone number is required");
        return false;
      }
      if(empty($email)){
        array_push($errors, "Email is required");
        return false;
      }
      if(empty($bmdc)){
        array_push($errors, "Bmdc is required");
        return false;
      }
      if(empty($name)){
        array_push($errors, "Name is required");
        return false;
      }
      if(empty($pass)){
        array_push($errors, "Password is required");
        return false;
      }
      if(empty($confirm_pass)){
        array_push($errors, "Confirm password is required");
        return false;
      }
      if($pass != $confirm_pass){
        array_push($errors, "Passwords do not match");
        return false;
      }
      return true;
    }

    if(validation($phone, $email, $bmdc, $name, $pass, $confirm_pass)){
      $sql = "INSERT INTO doctor (phone, email, bmdc_no, name, pass) VALUES ('$phone', '$email', '$bmdc', '$name', '$pass')";
      $result = $conn->query($sql);
      if($result){
        header("Location: /chillpill/");
      }
      else{
        array_push($errors, "Something went wrong");
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en" data-theme="emerald">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/chillpill/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillPill</title>
    <script type="module" crossorigin src="/chillpill/assets/modulepreload-polyfill.js"></script>
    <script type="module" crossorigin src="/chillpill/assets/anim.js"></script>
    <link rel="stylesheet" href="/chillpill/assets/login.css" />
  </head>
  <body>
    <canvas id="anim-canvas" class="z-0 absolute"></canvas>
    <div class="hero min-h-screen relative overflow-hidden">
      <div
        class="hero-content md:bg-primary-content p-10 md:p-20 rounded-xl md:shadow-xl flex-col lg:flex-row-reverse lg:gap-10"
      >
        <div class="text-center lg:text-left flex-1 lg:self-start md:text-secondary-content">
          <h1 class="text-5xl font-bold">
            TAKE A <br />
            <span class="text-secondary">CHILL</span>
            <span class="text-secondary">PILL</span>
          </h1>
          <p class="py-6">Start your journey towards a pragmatic and convenient medicare.</p>
        </div>
        <div class="card flex-shrink-0 w-full max-w-sm shadow-2xl bg-base-100">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
            <div class="card-body">
            <?php
              if(!empty($errors)){
                foreach($errors as $e){
                  echo <<<EOL
                  <div class="alert alert-error shadow-lg text-sm">
                    <div>
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="stroke-current flex-shrink-0 h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                      <span>$e</span>
                    </div>
                  </div>
                  EOL;
                }
              }
            ?>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Phone</span>
                </label>
                <input type="text" name="phone" placeholder="Phone Number" maxlength="11" class="input input-bordered" />
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Email</span>
                </label>
                <input type="email" name="email" placeholder="email" class="input input-bordered" />
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">BMDC Reg. no</span>
                </label>
                <input type="text" name="bmdc" placeholder="BMDC Reg. no" class="input input-bordered" />
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Name</span>
                </label>
                <input type="text" name="name" placeholder="Name" class="input input-bordered" />
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Password</span>
                </label>
                <input type="password" name="password" placeholder="password" class="input input-bordered" />
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Confirm Password</span>
                </label>
                <input type="password" name="confirm_pass" placeholder="confirm password" class="input input-bordered" />
              </div>
              <div class="form-control mt-6">
                <input type="submit" class="btn btn-primary" value="Register" />
              </div>
              <label class="label">
                <a href="/chillpill/" class="label-text-alt link link-hover ml-auto"
                  >Already have an account? Login</a
                >
              </label>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
