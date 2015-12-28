<?php 
if(!isset($_SESSION))
     session_start();
if(!isset($_SESSION['email']) || !isset($_SESSION['membership']))
 header('Location: ../homepage.php');
else
  $my_email = $_SESSION['email'];

require '../../../functions/security.php';
require '../../../db/connect.php';

if(isset($_POST["accept"]) || isset($_POST["reject"]))
{
  if(isset($_POST["accept"]))
    $response = 1;
  else
    $response = 0;
  $update = $db->prepare("CALL respond_to_friend_request(?,?,?)");
  $update->bind_param("ssi", $my_email, $_POST["email"], $response);
  $update->execute();
  
}




$records = array();
$result = $db->query("CALL get_friend_requests('{$my_email}')");
while($r = $result->fetch_assoc())
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
    <title>Game World | Friend Requests</title>

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
    <link href="friend_requests.css" rel="stylesheet">

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
        <h1 style="padding: 5px 15px">Friend Requests</h1>
        <hr style="border: solid 1px green; box-shadow: 0px 0px 3px black">
      <div class="row">
        <div class="col-md-6">
        <?php

          if(!count($records)) {
            ?>
            <br>
            <p style="font-size: 20px; padding-left: 15px">You have no friend requests.</p>
         <?php } else { ?>


                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Member Name</th>
                      <th>Email</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                         <?php
                         $c = 1;
                        foreach($records as $r){ ?>
                        
                        <tr>

                          <td><?php echo $r["First Name"],' ' ,$r["Last Name"]; ?></td>
                          <td><a href=<?php echo '../profile.php?email='.$r["Email"] ?>><?php echo $r["Email"]; ?></a></td>
                          <form action="" method="post">
                            <input type="text" name="email" value=<?php echo $r["Email"]; ?> hidden>
                            <td><input type="submit" name="accept" value="Accept" id="accept"></td>
                            <td><input type="submit" name="reject" value="Reject" id="reject"></td>
                          </form>
                        </tr>
                          
                      <?php $c++;} ?> 
                  </tbody>
                </table>

         <?php } ?>
         
         
            
              
          

          
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