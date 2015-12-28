<?php 
if(!isset($_SESSION))
     session_start();
if(!isset($_SESSION['email']))
  header('Location: ../../homepage.php');
else
  $my_email = $_SESSION['email'];
require '../../../functions/security.php';

if(isset($_POST["name"]))
  $name = escape(trim($_POST["name"]));
else
   if(isset($_GET["name"]))
      $name = escape(trim($_GET["name"]));
  else
    header("Location: ../arena.php");
 

if(isset($_GET["type"]))
  $type = escape(trim($_GET["type"]));
else
  $type = 'game';

require '../../../db/connect.php';

$records = array();
switch ($type) {
    case 'member':
      $result = $db->query(
              "SELECT * FROM Normal_Users WHERE first_name LIKE '%{$name}%' OR last_name LIKE '%{$name}%'");    
      while($r = $result->fetch_object())
      {
        $r->membership = "Normal User";
        $records[] = $r;
      }
      
      $result = $db->query(
              "SELECT * FROM Verified_Reviewers WHERE first_name LIKE '%{$name}%' OR last_name LIKE '%{$name}%'");    
      while($r = $result->fetch_object()) 
      {
        $r->membership = "Verified Reviewer";
        $records[] = $r;
      }

      $result = $db->query(
              "SELECT * FROM Development_Teams WHERE name LIKE '%{$name}%'");    
      while($r = $result->fetch_object())
      {
        $r->membership = "Development Team";
        $records[] = $r;
      } 
        
       
       
    break;
  case 'conference':
    $result = $db->query("SELECT * FROM Gaming_Conferences WHERE name LIKE '%{$name}%'");
    break;
    case 'community':
    $result = $db->query("SELECT * FROM Communities WHERE name LIKE '%{$name}%'");
    break;
  default:
    $type = 'game';
    $result = $db->query("SELECT * FROM Games WHERE name LIKE '%{$name}%'");
    break;
}
if($type != 'member')
  while($r = $result->fetch_object())
    $records[] = $r;

$result->free();
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
    <title>Game World | Search</title>

  <!-- Bootstrap core CSS -->
    <link href="../../../dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="../profile.css" rel="stylesheet">
    <link href="search.css" rel="stylesheet">

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
                <li><a href="friend_requests.php">View fiend requests</a></li>
                <?php } ?>
    
                <li role="separator" class="divider"></li>
                <li><a href="update_account.php">Update account</a></li>
                <li><a href="../../logout.php">Logout</a></li>
                
              </ul>
            </li>
          </ul>
          
          <form class="navbar-form navbar-right" method="post" action="search.php">
            <input type="text" class="form-control" name = "name" placeholder="Search...">
          </form>
        
      </div>
    </nav>
      

      <div class="header">
        <h1 style="padding: 5px 15px">Search results for:

        <?php echo $name;?>
         </h1>
        <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class=<?php if($type=='game')echo'active'; else echo '';?>><a href= <?php echo 'search.php?type=game&name=', $name; ?>>Games</a></li>
        <li role="presentation" class=<?php if($type=='conference')echo'active'; else echo '';?>><a href=<?php echo 'search.php?type=conference&name=', $name; ?>>Gaming Conferences</a></li>
        <li role="presentation" class=<?php if($type=='community')echo'active'; else echo '';?>><a href=<?php echo 'search.php?type=community&name=', $name; ?>>Communities</a></li>
        <li role="presentation" class=<?php if($type=='member')echo'active'; else echo '';?>><a href=<?php echo 'search.php?type=member&name=', $name; ?>>Members</a></li>
        
      </ul>
      <div class="row">
        <div class="col-md-6">
        <?php

          if(!count($records)) {
            ?>
            <br>
            <p style="font-size: 20px; padding-left: 15px">No results were found.</p>
         <?php }
         
         
            if(count($records) && $type == 'game'){ ?>
              <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Game Name</th>
                      <th>Release Date</th>
                      <th>Rating</th>
                    </tr>
                  </thead>
                  <tbody>
                         <?php
                         $c = 1;
                        foreach($records as $r){ ?>
                        
                        <tr>
                          <td><?php echo $c; ?></td>
                          <td><a href=<?php echo '../../games/game_info.php?game_id='.$r->game_no; ?>><?php echo escape($r->name); ?></a></td>
                          <td><?php echo escape(date("d-m-Y", strtotime($r->release_date))); ?></td>
                          <td><?php echo escape($r->rating); ?></td>
                        </tr>
                          
                      <?php $c++;} ?> 
                  </tbody>
                </table>
          <?php }



            if(count($records) && $type == 'member') { ?>
                <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Member Name</th>
                        <th>Email</th>
                        <th>Membership</th>
                      </tr>
                    </thead>
                    <tbody>
                           <?php
                           $c = 1;
                        foreach($records as $r){ ?>
                          <tr>
                            <td><?php echo $c; ?></td>
                            <td><a href=<?php echo "../profile.php?email=", $r->email;?>>

                            <?php 
                            if($r->membership == 'Development Team')
                                echo escape($r->name); 
                            else
                                echo escape($r->first_name), ' ',escape($r->last_name); 

                            ?>

                            </a></td>
                            <td><?php echo escape($r->email); ?></td>
                            <td><?php echo escape($r->membership); ?></td>
                          </tr>
                            
                        <?php $c++;} ?> 
                    </tbody>
                  </table>
          <?php }



            if(count($records) && $type == 'conference') { ?>
                <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Conference Name</th>
                        <th>Venue</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                      </tr>
                    </thead>
                    <tbody>
                           <?php
                           $c = 1;
                        foreach($records as $r){ ?>
                          <tr>
                            <td><?php echo $c; ?></td>
                            <td><a href="../../construction.php"><?php echo escape($r->name); ?></a></td>
                            <td><?php echo escape($r->venue); ?></td>
                            <td><?php echo escape($r->start_date); ?></td>
                            <td><?php echo escape($r->end_date); ?></td>
                          </tr>
                            
                        <?php $c++;} ?> 
                    </tbody>
                  </table>
          <?php }

            if(count($records) && $type == 'community') {?>
            <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Community Name</th>
                    <th>Theme</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>
                       <?php
                       $c = 1;
                    foreach($records as $r){ ?>
                      <tr>
                        <td><?php echo $c; ?></td>
                        <td><a href="../../construction.php"><?php echo escape($r->name); ?></a></td>
                        <td><?php echo escape($r->theme); ?></td>
                        <td><?php echo escape($r->description); ?></td>
                      </tr>
                        
                    <?php $c++;}  ?>    
                </tbody>
              </table>
          <?php
            }
          ?>

          
        </div>

      </div>
      
      

</div>
   <footer class="panel-footer">
        <p>&copy; 2015 Powered by Sagheer and Soliman</p>
    </footer>
    

    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../../assets/js/vendor/jquery.min.js"></script>')</script>
    <script src="../../../dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>