<?php require_once "config/db.php" ?>
<?php 
  $session_started = session_start();
  if(isset($_SESSION["loggedin"])){
    header("Location: /chillpill/dashboard/");
  }
  $errors = [];
  function login($conn, $identifier, $password) {
    echo "<script>console.log('Identifier: ".$identifier."')</script>";
    echo "<script>console.log('Pass: ".$password."')</script>";
    $usingPhone = is_numeric($identifier);
    global $errors;
    if($usingPhone){
      $sql = "SELECT * FROM doctor WHERE phone = '$identifier' AND pass = '$password'";
    }
    else
    {
      if(filter_var($identifier, FILTER_VALIDATE_EMAIL)){
        $sql = "SELECT * FROM doctor WHERE email = '$identifier' AND pass = '$password'";
      }
      else
      {
        array_push($errors, "Invalid Email Address");
        return;
      }
    }
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
      echo "<script>console.log('Login successful')</script>";
      $_SESSION['loggedin'] = true;
      $_SESSION['doctorId'] = $identifier;
      header("Location: dashboard/");
    }else{
      echo "<script>console.log('Login failed')</script>";
      array_push($errors, "Invalid Credentials");
    }
  }
  require_once "utils/sanitize.php";
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $identifier = sanitize_input($_POST["identifier"]);
    $password = md5( sanitize_input($_POST["password"]));
    if(!empty($identifier) && !empty($password))
      login($conn, $identifier, $password);
  }
?>
<!DOCTYPE html>
<html lang="en" data-theme="emerald">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/chillpill/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillPill | Login</title>
    <script type="module" crossorigin src="/chillpill/assets/modulepreload-polyfill.js"></script>
    <script type="module" crossorigin src="/chillpill/assets/anim.js"></script>
    <link rel="stylesheet" href="/chillpill/assets/login.css">
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
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Username</span>
                </label>
                <input type="text" name="identifier" placeholder="email / phone (without +88)" class="input input-bordered" required/>
              </div>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Password</span>
                </label>
                <input type="password" name="password" placeholder="password" class="input input-bordered" required/>
                <label class="label">
                  <a href="forgetPassword/" class="label-text-alt link link-hover text-gray-500"
                    >Forgot password?</a
                  >
                </label>
              </div>
              <div class="form-control mt-6">
                <input type="submit" class="btn btn-primary" value="Login"/>
              </div>
              <label class="label">
                <a href="register/" class="label-text-alt link link-hover ml-auto"
                  >Want to join with us?</a
                >
              </label>
            </form>

            
          </div>
        </div>
      </div>
    </div>
    
  </body>
</html>
