<?php
if(!isset($_SESSION))
    session_start();
if(!isset($_SESSION['email']))
    header("Location: ../../homepage.php");

$my_email = $_SESSION['email'];

$pp = '../../../db/users/pp/' . $my_email . '.jpg';


require '../../../db/connect.php';
$membership = "None";
$result = $db->query("SELECT * FROM Normal_Users WHERE email = '{$my_email}'");

if($result->num_rows)
{    
    $membership = "Normal User";
    $user = $result->fetch_object();
    
    if($user->first_name)
        $first_name = $user->first_name;
    else
        $first_name = '"tell us you first name."';
    if($user->last_name)
        $last_name = $user->last_name;
    else
        $last_name = '"tell us you last name."';
    if($user->birth_date)
         $birth_date = date("d-m-Y", strtotime($user->birth_date));
    else
      $birth_date = '"tell us your birth date."';

}
else
{
    $result = $db->query("SELECT * FROM Verified_Reviewers WHERE email = '{$my_email}'");

    if($result->num_rows)
    {    
        $membership = "Verified Reviewer";
        $user = $result->fetch_object();
        if($user->first_name)
        $first_name = $user->first_name;
        else
            $first_name = '"tell us you first name."';
        if($user->last_name)
            $last_name = $user->last_name;
        else
            $last_name = '"tell us you last name."';
        if(!($exp_years = $user->years_of_experience))
            $exp_years = '"tell us your years of experience."';
        
    }
    else
    {
        $result = $db->query("SELECT * FROM Development_Teams WHERE email = '{$my_email}'");

        if($result->num_rows)
        {    
            $membership = "Development Team";
            $user = $result->fetch_object();
            if($user->name)
                $team_name = $user->name;
            else
                $team_name = "tell us your team name.";
            if($user->formation_date)
                $formation_date = date("d-m-Y", strtotime($user->formation_date));
            else
                $formation_date = '"tell us your formation date."';
            if(!($company = $user->company))
                $company = "tell us your company.";
            
        }

    }
}    


if(isset($_POST['update']))
{
    switch ($membership) {
        case 'Normal User':
            if(isset($_POST['first_name']) && !empty($_POST['first_name']))
                $first_name = $_POST['first_name'];
            else
                $first_name = $user->first_name;

            if(isset($_POST['last_name']) && !empty($_POST['last_name']))
                $last_name = $_POST['last_name'];
            else
                $last_name = $user->last_name;

            if(isset($_POST['birth_date']) && !empty($_POST['birth_date']))
                $birth_date = date("Y-m-d", strtotime($_POST['birth_date']));
            else
                $birth_date = $user->birth_date;

            $update_member = $db->prepare("CALL update_normal_user(?,?,?,?)");
            $update_member->bind_param('ssss',$my_email, $first_name, $last_name, $birth_date);
            $update_member->execute();
            break;

        case 'Verified Reviewer':
            if(isset($_POST['first_name']) && !empty($_POST['first_name']))
                $first_name = $_POST['first_name'];
            else
                $first_name = $user->first_name;

            if(isset($_POST['last_name']) && !empty($_POST['last_name']))
                $last_name = $_POST['last_name'];
            else
                $last_name = $user->last_name;

            if(isset($_POST['exp_years']) && !empty($_POST['exp_years']))
                $exp_years = $_POST['exp_years'];
            else
                $exp_years = $user->years_of_experience;

            $update_member = $db->prepare("CALL update_verified_reviewer(?,?,?,?)");
            $update_member->bind_param('sssi',$my_email, $first_name, $last_name, $exp_years);
            $update_member->execute();
            break;
        
        case 'Development Team':
            if(isset($_POST['team_name']) && !empty($_POST['team_name']))
                $team_name = $_POST['team_name'];
            else
                $team_name = $user->name;

            if(isset($_POST['formation_date']) && !empty($_POST['formation_date']))
                $formation_date = $_POST['formation_date'];
            else
                $formation_date = $user->formation_date;

            if(isset($_POST['company']) && !empty($_POST['company']))
                $company = $_POST['company'];
            else
                $company = $user->company;

            $update_member = $db->prepare("CALL update_development_team(?,?,?,?)");
            $update_member->bind_param('ssss',$my_email, $team_name, $formation_date, $company);
            $update_member->execute();
            break;  
            break;
        default:
            
            break;
    }

    if(isset($_FILES["photo"]["name"]))
    {
        
        $target_dir = "../../../db/users/pp/";
        $target_file = $target_dir . $my_email . ".jpg";
        
        
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if(!$check || $imageFileType != "jpg" )
        {
            
            ?> <script>window.alert("Only .jpg images are allowed.")</script> <?php
            
        }
        else
        {

            if(file_exists($target_file))
                unlink($target_file);

            move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);         
        }
    }
    else
        die();
 
    header("Location: ../profile.php");
}

?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../images/basic/web_icon.png">

    <title>Game World | Update Account</title>

    <!-- Bootstrap core CSS -->
    <link href="../../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../profile.css" rel="stylesheet">

    <link href="update_account.css" rel="stylesheet">

    <link rel="icon" href="../../../images/basic/web_icon.png">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../../assets/js/ie-emulation-modes-warning.js"></script>

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
          <a class="navbar-brand" href="../arena.php" id = "home1"><img src = "../../../images/basic/web_icon.png">Game World</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../arena.php" id="home2">Home</a></li>
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" id="profile" data-toggle="dropdown" 
              role="button" aria-haspopup="true" aria-expanded="false">
              <?php echo $my_email; ?>
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href=<?php echo '../profile.php?email=',$my_email; ?>>View profile</a></li>
                <?php if($_SESSION['membership'] == "Normal User") {?>
                <li><a href="../message.php">View messages</a></li>
                <li><a href="friend_requests.php">View friend requests</a></li>
                <?php } ?>
    
                <li role="separator" class="divider"></li>
                <li><a href="#">Update account</a></li>
                <li><a href="../../logout.php">Logout</a></li>
                
              </ul>
            </li>
          </ul>
          
          <form class="navbar-form navbar-right" method="post" action="search.php">
            <input type="text" class="form-control" name = "name" placeholder="Search...">
          </form>
        
      </div>
    </nav>
          
    <div class="jumbotron placeholder" id="wrapper">
        <form method="post" action="" enctype="multipart/form-data">
            <fieldset>

               <img src=<?php echo $pp; ?>  onerror="this.src='../../../db/users/pp/NA.png'" id="pp" width="200px" height="200px" border-radius="50%">
                

                <div class="fileUpload">
                     <span><img src="../../../images/basic/cover.png"  id="cover"></span>
                     <input type="file" name="photo" id="photo" class="upload" />
                </div>
               <?php if($membership != "Development Team") { ?>
                 <div>
                    <input type="text" name="first_name" id="first_name" placeholder=<?php echo "'{$first_name}'"; ?>>
                </div>
                <div>
                    <input type="text" name="last_name" id="last_name" placeholder=<?php echo "'{$last_name}'"; ?>>
                </div>

                <?php  if($membership == "Normal User") { ?>
                <div>
                    <input type="text" name="birth_date" id="birth_date" placeholder=<?php echo $birth_date; ?>>
                </div>
                <?php } else { ?>

                <div>
                    <input type="text" name="exp_years" id="exp_years" placeholder=<?php echo $exp_years; ?>>
                </div>

                <?php }} else if($membership == "Development Team") { ?>
                <div>
                    <input type="text" name="team_name" id="team_name" placeholder=<?php echo "'{$team_name}'"; ?>>
                </div>
                <div>
                    <input type="text" name="company" id="company" placeholder=<?php echo "'{$company}'"; ?>>
                </div>
                <div>
                    <input type="text" name="formation_date" id="formation_date" placeholder=<?php echo $formation_date; ?>>
                </div>
                
                
                <?php } ?>
                <div>
                    <input type="submit" name="update" value="Update"/>    
                </div>
            </fieldset>
        </form>
    </div>
    
    <footer class="panel-footer">
        <p>&copy; 2015 Powered by Sagheer and Soliman</p>
    </footer>
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../../../dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../../assets/js/ie10-viewport-bug-workaround.js"></script>


    <script src="../../_includes/js/jquery.js"></script>
    <script>
    $(document).ready(function(){

               
        $("#pp").mouseenter(function(){

            $("#cover").fadeIn(400);
            $(".upload").css("z-index", 1);

        });

        $("#cover").mouseleave(function(){

            $("#cover").fadeOut(400);
            $(".upload").css("z-index", -1);

        });

        $(".upload").mouseleave(function(){

            $("#cover").fadeOut(400);
            $(".upload").css("z-index", -1);

        });


    });




    </script>
</tbody>
</html>
