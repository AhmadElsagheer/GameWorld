<?php 
if(!isset($_SESSION))
     session_start();
if(!isset($_SESSION['email']))
    header("Location: ../homepage.php");

$my_email = $_SESSION['email'];

if(isset($_POST["add_friend"]) && isset($_GET["email"]))
{
    $email = $_GET["email"];
    require '../../db/connect.php';
    $update = $db->prepare("CALL send_friend_request(?, ?)");
    $update->bind_param('ss', $my_email, $email);
    $update->execute();    
}

if(!isset($_GET['email']))
    $email = $my_email;
else
    $email = trim($_GET['email']);

require '../../db/connect.php';

$membership = "None";
$result = $db->query("SELECT * FROM Normal_Users WHERE email = '{$email}'");
$pp = '../../db/users/pp/' . $email . '.jpg';
if($result->num_rows)
{    
    $membership = "Normal User";
    $user = $result->fetch_object();
    $first_name = $user->first_name;
    $last_name = $user->last_name;
    if($user->birth_date)
    {
         $birth_date = date("d-m-Y", strtotime($user->birth_date));
         $age = $user->age;
    }   
    else
    {
      $birth_date = "Unknown";
      $age = "Unknown";  
    } 
    
    

}
else
{
    $result = $db->query("SELECT * FROM Verified_Reviewers WHERE email = '{$email}'");

if($result->num_rows)
{    
    $membership = "Verified Reviewer";
    $user = $result->fetch_object();
    $first_name = $user->first_name;
    $last_name = $user->last_name;
    if(!($exp_years = $user->years_of_experience))
        $exp_years = "Unknown";
    
}
else
{
    $result = $db->query("SELECT * FROM Development_Teams WHERE email = '{$email}'");

    if($result->num_rows)
    {    
        $membership = "Development Team";
        $user = $result->fetch_object();
        if($user->name)
            $name = $user->name;
        else
            $name = "Unknown";
        if($user->formation_date)
            $formation_date = date("d-m-Y", strtotime($user->formation_date));
        else
            $formation_date = "Unknown";
        if(!($company = $user->company))
            $company = "Unknown";
        
    }

}

 
}
require '../../db/connect.php';
$genre = $db->query("SELECT * FROM Members WHERE email = '{$my_email}'");
$genre = $genre->fetch_object();
$genre = $genre->preferred_game_genre;
if($genre)
    $genre = ', prefers '. $genre . ' games';
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../images/basic/web_icon.png">

    <title>Game World | Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="profile.css" rel="stylesheet">

    <link rel="icon" href="../../images/basic/web_icon.png">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>


  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" >
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="arena.php" id = "home1"><img src = "../../images/basic/web_icon.png">Game World</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="arena.php" id="home2">Home</a></li>
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" id="profile" data-toggle="dropdown" 
              role="button" aria-haspopup="true" aria-expanded="false">
              <?php echo $my_email; ?>
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href=<?php echo 'profile.php?email=',$my_email; ?>>View profile</a></li>
                <?php if($_SESSION['membership'] == "Normal User") {?>
                
                <li><a href="message.php">View messages</a></li>
                <li><a href="actions/friend_requests.php">View friend requests</a></li>
                <?php } ?>
    
                <li role="separator" class="divider"></li>
                <li><a href="actions/update_account.php">Update account</a></li>
                <li><a href="../logout.php">Logout</a></li>
                
              </ul>
            </li>
          </ul>
          
          <form class="navbar-form navbar-right" method="post" action="actions/search.php">
            <input type="text" class="form-control" name = "name" placeholder="Search...">
          </form>
        
      </div>
    </nav>
          
    <div class="jumbotron placeholder">
        <img src = <?php echo $pp ?>  onerror="this.src='../../db/users/pp/NA.png'" width="200px" height="200px" border-radius="50%">
        <h1>
            <?php  
                if($membership == "Development Team")
                    echo $name;
                else
                    echo $first_name, ' ',$last_name;
            ?>

        </h1>
        <p class="lead"><?php echo $membership ?><?php echo $genre ?></p>
        

        <?php
            if($membership == "Normal User")
            {
        ?>
            <p><?php echo 'Birth Date: ', $birth_date;?></p>
            <p><?php echo 'Age: ',$age;?></p>
            <?php 

            require '../../db/connect.php';
            $check = $db->query("SELECT * FROM Friends WHERE ('{$my_email}' = user2 AND '{$email}' = user1) OR ('{$my_email}' = user1 AND '{$email}' = user2)");
            if(!$check->num_rows && $email != $my_email && $_SESSION["membership"] == "Normal User")
            {

            ?>
            <form method="post" action="">
            <input type="submit" name="add_friend" value="Add Friend" id="friend" class="btn btn-lg btn-primary">
            </form>
        <?php
            }}
            else
                if($membership == "Development Team")
                {
        ?>
            <p><?php echo 'Formation Date: ', $formation_date;?></p>
            <p><?php echo 'Company: ',$company;?></p>
        <?php
                }
                else
                    if($membership == "Verified Reviewer")
                    {
        ?>
            <p><?php echo 'Years of Experience: ', $exp_years;?></p>
        
        <?php
                    }
                    else
                        header("Location: ../homepage.php");

        ?>

        
        
    </div>
    
    <footer class="panel-footer">
        <p>&copy; 2015 Powered by Sagheer and Soliman</p>
    </footer>
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

