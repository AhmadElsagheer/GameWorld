<?php 
if(!isset($_SESSION))
     session_start();
if(!isset($_SESSION['email']))
  header('Location: ../homepage.php');
else
  $my_email = $_SESSION['email'];

require '../../db/connect.php';
$top_recommend = $db->query("CALL top_ten_game_recommendations('$my_email')");

require '../../db/connect.php';
$my_recommend = $db->query("CALL view_game_recommendations('$my_email')");

?>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    
    <link rel="icon" href="../../images/basic/web_icon.png">
    <title>Game World | Find Games</title>

    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="../members/profile.css" rel="stylesheet">
    <link href="games_arena.css" rel="stylesheet">

  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top" >
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="../members/arena.php" id = "home1"><img src = "../../images/basic/web_icon.png">Game World</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../members/arena.php" id="home2">Home</a></li>
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" id="profile" data-toggle="dropdown" 
              role="button" aria-haspopup="true" aria-expanded="false">
              <?php echo $my_email; ?>
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href=<?php echo '../members/profile.php?email=',$my_email; ?>>View profile</a></li>
                <?php if($_SESSION['membership'] == "Normal User") {?>
                
                <li><a href="../members/message.php">View messages</a></li>
                <li><a href="../members/actions/friend_requests.php">View fiend requests</a></li>
                <?php } ?>
    
                <li role="separator" class="divider"></li>
                <li><a href="../members/actions/update_account.php">Update account</a></li>
                <li><a href="../logout.php">Logout</a></li>
                
              </ul>
            </li>
          </ul>
          
          <form class="navbar-form navbar-right" method="post" action="../members/actions/search.php">
            <input type="text" class="form-control" name = "name" placeholder="Search...">
          </form>
        
      </div>
    </nav>
      

      <div class="header">
       	<h3>Top Ten Games<span style="display:block; font-size:18px">based on recommendations</span></h3>
       	  
       		<?php 

            while($t = $top_recommend->fetch_assoc()){
              require '../../db/connect.php';
              $game_name = $t["game_name"];
              $game = $db->query("SELECT * FROM Games WHERE name = '{$game_name}'");
              $game = $game->fetch_object();
              $background = 'background: url(' .$game->game_no. '/screenshots/'. $game->game_no.'_s1.jpg)'; 
              ?>
                
                <div class="row row-panel review" style= <?php echo  "'{$background}'";?>>
                <a class="game-title" href=<?php echo "game_info.php?game_id=" . $game->game_no;?>><?php echo $game_name;?></a>
              
                </div>

              <?php
            }
          ?>
       </div>
       
       
	
      


   <footer class="panel-footer">
        <p>&copy; 2015 Powered by Sagheer and Soliman</p>
    </footer>
    

    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../../assets/js/vendor/jquery.min.js"></script>')</script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

    <script src="../../_includes/js/jquery.js"></script>
   
    <script>
    $(document).ready(function(){

      $("h3").click(function(){
        
        $(this).next().slideToggle("slow");


      });

      $(".review").mouseenter(function(){

        $(this).fadeTo("slow", 0.7);
      });

      $(".review").mouseleave(function(){

        $(this).fadeTo("slow", 1);
      });

    });
  </script>
  </body>
</html>