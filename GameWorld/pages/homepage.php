<?php 
  if(!isset($_SESSION))
     session_start();
  
  if(isset($_SESSION['email']))
     header("Location: members/arena.php");

  if(isset($_POST['sign_in']))
  {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(validateLogin($email,md5($password))){
        header("Location: ../pages/members/arena.php");
  }
    else
    { 
?>
      <script>window.alert("Invalid email/password!")</script>
<?php
   
    }
  }
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Connect all gamers together.">
    <meta name="author" content="Ahmad Elsagheer and Ahmed Soliman">
    <link rel="icon" href="../images/basic/web_icon.png">

    <title>Game World | Homepage</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="homepage.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <!-- <body background="../images/homepage/background.jpg"> -->
  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="" id = "home"><img src = "../images/basic/web_icon.png">Game World</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" method="post" action="">
            <div class="form-group">
              <input type="email" name = "email" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" name="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success" name = "sign_in" id = "sign_in">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Welcome to Game World!</h1>
        <p>Meet gamers from all over the world, keep updated with latest games and on fire discussions!</p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4" >
          <h2>Get in the community</h2>
          <img src = "../images/homepage/normal_user.jpg" id = "normal_user">
          <p>view all games, rate them, attend gaming conferences.</p>
          <p><a class="btn btn-default" href="members/actions/sign_up.php?membership=normal_user" role="button">Sign up for a normal user &raquo;</a></p>
        </div>
        <div class="col-md-4" >
          <h2>Review Games</h2>
          <img src = "../images/homepage/verified_reviewer.jpg" id = "verified_reviewer">
          <p>Write your own game reviews on the new arrivals.</p>
          <p><a class="btn btn-default" href="members/actions/sign_up.php?membership=verified_reviewer" role="button">Sign up for a verified reviewer &raquo;</a></p>
       </div>
        <div class="col-md-4" >
          <h2>The Game is Yours</h2>
          <img src = "../images/homepage/development_team.jpg" id = "development_team">
          <p>Develop your own game and present it in a gaming conferences.</p>
          <p><a class="btn btn-default" href="members/actions/sign_up.php?membership=development_team" role="button">Sign up for a development team &raquo;</a></p>
        </div>
      </div>

      

      
    </div> <!-- /container -->
  <footer class="panel-footer">
        <p>&copy; 2015 Powered by Sagheer and Soliman</p>
      </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

<?php
function validateLogin($email,$password){
  $db = new mysqli('127.0.0.1', 'root', '', 'GameWorld');

  $result = $db->query("SELECT * FROM Members WHERE email = '{$email}' AND password = '{$password}'");
  if($result->num_rows)
  {
    $result = $result->fetch_object();
    $my_email = $result->email;
    $_SESSION['email'] = $my_email;
    
    $result = $db->query("SELECT * FROM Normal_Users WHERE email = '{$my_email}'");

    if($result->num_rows)
      $membership = "Normal User";
    else
    {
        $result = $db->query("SELECT * FROM Verified_Reviewers WHERE email = '{$my_email}'");
        if($result->num_rows)
            $membership = "Verified Reviewer";
        else
            if($result->num_rows) 
                $membership = "Development Team";
    }    
    $_SESSION['membership'] = $membership;
    return true;
  }
  return false;
}
?>
