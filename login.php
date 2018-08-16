<?php

  include_once('content/config/dbconnect.php');

  $Admin_name = "";
  $Admin_Password = "";

  if (isset($_POST['login'])) {
    $Admin_name = $_POST['name'];
    $Admin_Password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE Admin_name='$Admin_name'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);

    if ($count == 1 && $row['Admin_Password']) {
      session_start();
      $_SESSION['Admin_name'] = $row['Admin_name'];
      header("refresh:1; url=content/index.php");
    }
    else{
      $errormsg = mysqli_error($conn).'Invalid name or Password';
      header("location:login.php");
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link type="text/css" href="assets/custom/css/loginstyle.css" rel="stylesheet">
</head>
<body>
  <form class="form-vertical" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off">
    <div class="login-page">
      <div class="form">
        <form class="login-form">
          <input type="text" name="name" placeholder="username"/>
          <input type="password" name="password" placeholder="password"/>
          <button type="submit" name="login">login</button>
          <p class="message"><a href="#">WELCOME admin</a></p>
        </form>
      </div>
    </div>
  </form>  
</body>
</html>
<script>
  $('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
  });
</script>