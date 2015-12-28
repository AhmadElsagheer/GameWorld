<?php 
if(!isset($_SESSION))
     session_start();
if(!isset($_SESSION['email']) || !isset($_SESSION['membership']))
 header('Location: ../homepage.php');
else
  $my_email = $_SESSION['email'];

require '../../functions/security.php';
require '../../db/connect.php';

$friend_list = $db->query("CALL view_friends('{$my_email}')");

if(isset($_GET["email"]))
{
  $friend_email = $_GET["email"];
  

if(isset($_POST['send']) && isset($_POST['message']) && !empty(trim($_POST['message'])))
{
  $message = trim($_POST['message']);
  require '../../db/connect.php';
  $send_message = $db->prepare("CALL send_message(?, ?, ?)");
  $send_message->bind_param('sss', $my_email, $friend_email, $message);
  $send_message->execute();
  header("Location: message.php?email=".$friend_email);
}


  require '../../db/connect.php';
  $check = $db->query("SELECT * FROM Friends 
    WHERE (('{$my_email}' = user2 AND '{$friend_email}' = user1) OR ('{$my_email}' = user1 AND '{$friend_email}' = user2))
    AND accepted = 1");

  if($check->num_rows)
  {
    
      $result = $db->query("CALL show_messages('{$my_email}', '{$friend_email}')");
      $messages = array();
      while($m = $result->fetch_object())
        $messages[] = $m;
      $result->free();
  }
  else
      header('Location: message.php');
  
  

  
  
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
    
    <link rel="icon" href="../../images/basic/web_icon.png">
    <title>Game World | Messages</title>

  <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="profile.css" rel="stylesheet">
    <link href="message.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
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
                <li><a href="actions/friend_requests.php">View fiend requests</a></li>
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
      
    
        <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          
           
           
           <p style="text-align:center; font-size:20px; font-family:Verdana; color: #428bca">Friend List</p>
           <hr style="box-shadow: 0px 0px 5px black">
          <ul class="nav nav-sidebar" >
            <?php
              while($friend = $friend_list->fetch_assoc()) { ?>
                
                <li class=<?php if(isset($friend_email) && $friend_email == $friend["Email"]) echo "active"?>>
                <a href=<?php echo "message.php?email=".$friend["Email"];?>>
                  <?php echo $friend["Email"];?>
                 
                 </a></li>
              
            <?php
              }
            ?>
          </ul>
          
        </div>



      <div class="header">
      
        <h1 style="padding: 5px 15px">
        <?php 

        if(!isset($friend_email)){ ?>

        Find your friends and chat with them.
        

        <?php } else { ?>

        <a href=<?php echo "profile.php?email=".$friend_email;?>><?php echo $friend_email;?></a>

        <?php } ?>

        </h1>

           <div class="row">
        <div class="col-md-6">
      
        <?php

          if(isset($friend_email)) {
            ?>
        
            
        
                
                <div class="message-thread">
                <div class "new-message">
                    <form action="" method="post">
                      <textarea name = "message" id="message" placeholder="Write a message..."></textarea>
                      
                      <input type="submit" name="send" value="Send" id="send">

                    </form>
                </div>
                         <?php
                          
                        foreach($messages as $m){ ?>
                        
                        <div class="message-row">
                        

                          <p class=

                          <?php if($m->sender == $my_email) echo "'message message-me'"; else echo "'message message-friend'";?>
                          ><?php echo $m->sender; ?></p>
                          <p class="message message-content"><?php echo $m->content; ?></p>
                          <p class="message message-date"><?php echo $m->date_time; ?></p>
                          
                        
                        </div>
                        
                          
                      <?php } ?> 
                  
                  
                </div>
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
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"></script>')</script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>