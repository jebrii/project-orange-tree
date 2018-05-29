<?php

# session and global elements
  $navbar = file_get_contents ( "http://www.hraschke.com/resources/navbar.html" );

# See if already logged in
/* if ( $_COOKIE["activeUser"] ) {
  header("Location: logged-in.php");
} */

# db connection
  $db_server = "shareddb-i.hosting.stackcp.net";
  $db_user = "default-33357fb9";
  $db_pass = "gKzyk[w@Uve6Z8ae";
  $db_name = "default-33357fb9";

  $link = mysqli_connect( $db_server, $db_user, $db_pass, $db_name );
  if ( mysqli_connect_error() ) {
    $create_entry = false;
    die ( "Error connecting to server." );
  }

# variables and functions for this page
  $req_email = "";
  $req_username = "";
  $req_password = "";

  $create_entry = true;

  function assignParams ($link, $param) {
    if ( $_POST[$param]) {
      return mysqli_real_escape_string( $link, $_POST[$param] );
    } else {
      $create_entry = false;
      return false;
    }
  }

  function checkUniqueRecord ( $link, $tbl_name, $record, $req ) {
    $query = "SELECT * FROM `$tbl_name` WHERE `$record` = '$req'";
    $result = mysqli_query( $link, $query );
    if ( $result ) {
      if ( mysqli_num_rows($result) > 0 ) {
        echo "<p style='color:red;'>That $record is already taken.</p>";
        return false;
      } else {
        return true;
      }
    } else {
      echo "<p>There was a problem accessing the database! Please try again later.</p>";
      return false;
    }
  }

  function userSignup ( $link, $email, $username, $password ) {
    $query = "INSERT INTO `users` (`email`, `username`, `password`) VALUES ('$email', '$username', '$password')";
    if ( $result = mysqli_query( $link, $query ) ) {
      return true;
    } else {
      return false;
    }
  }

# process POST signup request
  if ( $_POST ) {
    $req_email = assignParams($link, "email");
    $req_username = assignParams($link, "username");
    $req_password = assignParams($link, "password");
    if ( checkUniqueRecord($link, "users", "email", $req_email) && checkUniqueRecord($link, "users", "username", $req_username) ) {
      if ( userSignup ( $link, $req_email, $req_username, $req_password ) ) {
        setcookie( "activeUser", $req_username, time() + 43200 );
        header("Location: session.php");
      } else {
        echo "<p>There was an unknown error signing you up! Please try again later.</p>";
      }
    }
  }


 ?>
<html>
<head>
  <title>Signup Form</title>

  <!--Bootstrap meta tags & styelsheet-->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />

  <!-- Custom styles--->
  <link rel="stylesheet" type="text/css" href="/resources/stylesheets/main.css" />
  <style>

  html {
    height: 100%;
  }

  body {
    background-image: url("../resources/ocean-sunset-bg.jpg");
    height: 100%;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  }

  textarea {
    resize: none;
  }

  .form-check-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
  }

  .form-check-label {
    padding-left: 48px;
    position: relative;
    left: -24px;
  }

  /* Create a custom checkbox */
  .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #eee;
  }

  .check-cont {
    position: relative;
    left: 0;
    width: 240px;
  }

  /* On mouse-over, add a grey background color */
  .check-cont:hover input ~ .checkmark {
    background-color: #ccc;
  }

  /* When the checkbox is checked, add a blue background */
  .check-cont input:checked ~ .checkmark {
    background-color: #2196F3;
  }

  /* Create the checkmark/indicator (hidden when not checked) */
  .checkmark:after {
    content: "";
    position: absolute;
    display: none;
  }

  /* Show the checkmark when checked */
  .check-cont input:checked ~ .checkmark:after {
    display: block;
  }

  /* Style the checkmark/indicator */
  .check-cont .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
  }

  h1 {
    margin: 20px 0;
  }

  label, button, li a {
    font-size: 120%;
    font-weight: bold;
    color: white;
  }

  #topmost-container {
    margin-top: 80px;
    width: 70%;
  }

  #topmost-container .nav-tabs {
      border: none;
  }

  .nav-tabs .nav-item .nav-link {
    border-bottom: 1px solid #FFFFFF;
  }

  .nav-tabs .nav-item .active {
    background-color: rgba(255,255,255,0.4) !important;
    border-radius: 12px 12px 0 0;
    border-bottom: none !important;
  }

  .tab-content {
    padding: 64px 64px 24px 64px;
    background-color: rgba(255,255,255,0.4);
    border: 1px solid #FFFFFF;
    border-top: none;
    border-radius: 0 0 12px 12px;
  }

  .error-message {
    color: red;
    font-style: italic;
    display: none;
    margin: 5px 0 0 0;
    font-size: 80%;
  }

  .btn {
    margin-left: 12px;
    margin-right: 12px;
  }

  .page-footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 60px;
    line-height: 20px;
    background-color: #007FFA;
    padding-top: 10px;
  }

  .page-footer p {
    color: white !important;
    text-align: center;
    font-size: 70%;
    font-style: italic;
    margin: 0 auto;
  }

  </style>

</head>
<body>

<!-- NAVBAR -->
  <? echo $navbar; ?>

<!-- MAIN PAGE -->
  <div id="topmost-container" class="container">
    <ul class="nav nav-tabs nav-fill" role="tablist">
      <li class="nav-item"><a class="nav-link active" href="#signup-tab-content" data-toggle="tab" role="tab">Sign Up</a></li>
      <li class="nav-item"><a class="nav-link" href="#login-tab-content" data-toggle="tab" role="tab">Log In</a></li>
    </ul>

    <div class="tab-content">
      <div id="signup-tab-content" class="tab-pane fade show active" role="tabpanel">
        <form method="post" name="signup-form" onsubmit="return validateSubmitForm();">
          <div class="form-group row">
            <label for="signup-email-input" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
              <input type="text" name="email" class="form-control" id="signup-email-input" placeholder="Your email address..." data-toggle="popover" />
              <p id="signup-email-error" class="error-message">Please enter a valid email address.</p>
            </div>
          </div>
          <div class="form-group row">
            <label for="signup-username-input" class="col-sm-3 col-form-label">Username</label>
            <div class="col-sm-9">
            	<input type="text" name="username" class="form-control" id="signup-username-input" placeholder="Your desired username..." />
              <p id="signup-username-error-0" class="error-message">Please enter a username.</p>
              <p id="signup-username-error-1" class="error-message">Your username must be between 6 and 20 characters.</p>
              <!-- VALIDATE ON-PAGE LATER <p id="signup-username-error-2" class="error-message">That username is already taken.</p> -->

          	</div>
          </div>
          <div class="form-group row">
            <label for="signup-password-input" class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
            	<input type="password" name="password" class="form-control" id="signup-password-input" placeholder="Enter a password..." />
              <p id="signup-password-error-1" class="error-message password-error">Your password must be between 6 and 24 characters</p>
              <p id="signup-password-error-2" class="error-message password-error">Your password must contain at least one number</p>
              <p id="signup-password-error-3" class="error-message password-error">Your password must contain at least one uppercase letter</p>
              <p id="signup-password-error-4" class="error-message password-error">Your password must contain at least one lowercase letter</p>
              <p id="signup-password-error-5" class="error-message password-error">Your password must contain at least one special character</p>
          	</div>
          </div>
          <div class="form-group row">
            <label for="signup-confirm-input" class="col-sm-3 col-form-label">Confirm</label>
            <div class="col-sm-9">
            	<input type="password" name="confirm-password" class="form-control" id="signup-confirm-input" placeholder="Type your password again..." />
            	<p id="signup-confirm-error-0" class="error-message">Please enter your password again.</p>
            	<p id="signup-confirm-error-1" class="error-message">Your passwords do not match!</p>
          	</div>
          </div>
          <div class="form-group row">
            <div class="col-sm-4"></div>
            <button id="signup-reset-button" type="reset" class="btn btn-secondary col-sm-3">Reset</button>
            <button id="signup-submit-button" type="submit" class="btn btn-primary col-sm-3">Sign me up!</button>
            <div class="col-sm-4"></div>
          </div>
        </form>
      </div>

      <div id="login-tab-content" class="tab-pane fade" role="tabpanel">
        <form method="post" name="login-form" onsubmit="return validateLoginForm();">
          <div class="form-group row">
            <label for="login-username-input" class="col-sm-3 col-form-label">Username</label>
            <div class="col-sm-9">
            	<input type="text" name="username" class="form-control" id="login-username-input" placeholder="Your username..." />
              <p id="login-username-error" class="error-message">Please enter a username.</p>
          	</div>
          </div>
          <div class="form-group row">
            <label for="login-password-input" class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
            	<input type="password" name="password" class="form-control" id="login-password-input" placeholder="Your password..." />
              <p id="login-password-error" class="error-message">Please enter your password.</p>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-3 col-form-label"></div>
            <div class="col-sm-9">
              <div class="form-check check-cont">
                <input type="checkbox" name="login-check" value="makeCookie" class="form-check-input" id="login-checkbox" checked />
                <span class="checkmark"></span>
                <label class="form-check-label" for="login-checkbox">Keep me logged in</label>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-4"></div>
            <button id="login-reset-button" type="reset" class="btn btn-secondary col-sm-3">Reset</button>
            <button id="login-submit-button" type="submit" class="btn btn-primary col-sm-3">Log me in!</button>
            <div class="col-sm-4"></div>
          </div>
        </form>
      </div>
    </div>

  </div>

<!-- FOOTER -->
  <footer class="page-footer">
    <div class="container">
      <p class="text-muted">Site built from scratch without templates in html, css, javascript, jquery, bootstrap, and php.</p>
      <p class="text-muted">Last update: April 2018.</p>
      <a style="background-color:black;color:white;text-decoration:none;padding:4px 6px;font-family:-apple-system, BlinkMacSystemFont, &quot;San Francisco&quot;, &quot;Helvetica Neue&quot;, Helvetica, Ubuntu, Roboto, Noto, &quot;Segoe UI&quot;, Arial, sans-serif;font-size:12px;font-weight:bold;line-height:1.2;display:inline-block;border-radius:3px;position: relative;float:right;top:-32px;" href="https://unsplash.com/@7bbbailey?utm_medium=referral&amp;utm_campaign=photographer-credit&amp;utm_content=creditBadge" target="_blank" rel="noopener noreferrer" title="Photo Credit: Unsplash"><span style="display:inline-block;padding:2px 3px;"><svg xmlns="http://www.w3.org/2000/svg" style="height:12px;width:auto;position:relative;vertical-align:middle;top:-1px;fill:white;" viewBox="0 0 32 32"><path d="M20.8 18.1c0 2.7-2.2 4.8-4.8 4.8s-4.8-2.1-4.8-4.8c0-2.7 2.2-4.8 4.8-4.8 2.7.1 4.8 2.2 4.8 4.8zm11.2-7.4v14.9c0 2.3-1.9 4.3-4.3 4.3h-23.4c-2.4 0-4.3-1.9-4.3-4.3v-15c0-2.3 1.9-4.3 4.3-4.3h3.7l.8-2.3c.4-1.1 1.7-2 2.9-2h8.6c1.2 0 2.5.9 2.9 2l.8 2.4h3.7c2.4 0 4.3 1.9 4.3 4.3zm-8.6 7.5c0-4.1-3.3-7.5-7.5-7.5-4.1 0-7.5 3.4-7.5 7.5s3.3 7.5 7.5 7.5c4.2-.1 7.5-3.4 7.5-7.5z"></path></svg></span><span style="display:inline-block;padding:2px 3px;">Barth Bailey</span></a>
    </div>
  </footer>
<!-- SCRIPTS -->
  <!--jQuery, Popper, & Bootstrap etc. -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <!-- Custom scripts -->
  <script type="text/javascript">


  </script>
</body>
</html>
