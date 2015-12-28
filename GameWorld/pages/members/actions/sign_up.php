<?php 
  if(isset($_POST['first_name']))
    echo  $_POST['first_name'];
  if(!isset($_SESSION)) 
     session_start();
    
  if(isset($_SESSION['email']))
     header("Location: ../arena.php");

  if(isset($_POST['sign_in']))
  {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(validateLogin($email,$password)){
        header("Location: ../arena.php");
  }
    else
    { 
?>
      <script>window.alert("Invalid email/password!")</script>
<?php
   
    }


  }

  if(isset($_POST['email'], $_POST['password1'], $_POST['password2'], $_POST["membership"]))
  {
    include('../../../classes/registerClass.php');
    $r = new Register();
    
    if($r->register())
    {
      
      $_SESSION['email'] = $r->get_email();
      $_SESSION['membership'] = $r->get_membership();
      header("Location: ../arena.php");
    }
    else
    { 
      $errors = $r->get_errors();
    }
  }

  $normal = false;
  $reviewer = false;
  $team = false;
  if(isset($_GET["membership"]))
  {
    if($_GET["membership"] == "development_team")
      $team = true;
    else
      if($_GET["membership"] == "verified_reviewer")
        $reviewer= true;
      else
        $normal = true;
  }
  else
    $normal = true;
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Connect all gamers together.">
    <meta name="author" content="Ahmad Elsagheer and Ahmed Soliman">
    <link rel="icon" href="../../../images/basic/web_icon.png">

    <title>Game World | Sign Up</title>

    <!-- Bootstrap core CSS -->
    <link href="../../../dist/css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../homepage.css" type="text/css" rel="stylesheet">

    <link href="sign_up.css" type="text/css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

<body>

      <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="../../homepage.php" id = "home"><img src = "../../../images/basic/web_icon.png">Game World</a>
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









    <div id="wrapper">
        <form method="post" action="">
            <fieldset>
                <legend>Register Form</legend>
                <div class="errors">
                  <p <?php if(!isset($errors)) { ?> hidden <?php }?>>

                    <?php 
                      if(isset($errors))
                        foreach($errors as $e)
                          echo $e.'<br>';


                    ?>
                    


                  </p>

                </div>
                <div class = "placeholder">
                    <input type="text" name="email" placeholder="Email*"/> 
                </div>
                <div class = "placeholder">
                    <input type="password" name="password1" placeholder="Password*"/>
                </div>
                <div class = "placeholder">
                    <input type="password" name="password2" placeholder="Confirm Password*"/>
                </div>
                <div class = "placeholder">
                    <input type="text" name="pgg" placeholder="Preferred Game Genre"/>
                </div>
                <div class = "sub-info">
                <input type="hidden" name="membership" 

                value=<?php if($normal) echo "'Normal User'"; else if($reviewer) echo "'Verified Reviewer'"; else echo "'Development Team'"; ?>>
                <select name="membership" id="membership" disabled="">
                  <option value="Normal User" <?php if($normal == 1) echo 'selected'; ?>>Normal User</option>
                  <option value="Verified Reviewer" <?php if($reviewer == 1) echo 'selected'; ?>>Verified Reviewer</option>
                  <option value="Development Team" <?php if($team == 1) echo 'selected'; ?>>Development Team</option>
                </select>
                    
                <div>
                    <input type="text" name="first_name" id="first_name" placeholder="First Name"/>
                </div>
                <div>
                    <input type="text" name="last_name" id="last_name" placeholder="Last Name"/>
                </div>
                <div>
                    <input type="text" name="birth_date" id="birth_date" placeholder="Birth Date (YYYY-MM-DD)"/>
                </div>
                <div>
                    <input type="text" name="team_name" id="team_name" placeholder="Team Name"/>
                </div>
                <div>
                    <input type="text" name="company" id="company" placeholder="Company"/>
                </div>
                <div>
                    <input type="text" name="formation_date" id="formation_date" placeholder="Formation Date (YYYY-MM-DD)"/>
                </div>
                <div>
                    <input type="text" name="exp_years" id="exp_years" placeholder="Years of Experience"/>
                </div>
                </div>
                <div class="btn-bottom">
                <input type="submit" name="submit" value="Register"/>
                <input type="button" value="Change Membership"/>
                <div>
            </fieldset>    
        </form>
    </div>
    
    <script src="../../../_includes/js/jquery.js"></script>
    <script>

    $(document).ready(function(){
    changeFileds();
    $(":button").click(function() {$("#membership").prop("disabled", false);$("#membership").css("color", "black");});
      
    $("#membership").change(function() {changeFileds();});

    


  });
    
      function changeFileds() {

      $(".sub-info input[type='text']").hide();
      switch($("#membership").val())
      {
        case "Normal User": 
          $("#first_name").show();
          $("#last_name").show();
          $("#birth_date").show();
          break;
        case "Verified Reviewer": 
          $("#first_name").show();
          $("#last_name").show();
          $("#exp_years").show();
          break;
        case "Development Team": 
          $("#team_name").show();
          $("#company").show();
          $("#formation_date").show();
          break;
      }


    }
    
    </script>
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
