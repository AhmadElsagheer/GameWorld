<?php 
if(!isset($_SESSION))
     session_start();
if(!isset($_SESSION['email']))
 header('Location: ../homepage.php');
else
  $my_email = $_SESSION['email'];

require '../../functions/security.php';
require '../../db/connect.php';

if(!isset($_GET["game_id"], $_GET["reviewer"]))
  header('Location: ../homepage.php');


$game_id = $_GET["game_id"];
$reviewer = $_GET["reviewer"];
  
require '../../db/connect.php';
$find_game = $db->query("SELECT * FROM Games WHERE game_no = '{$game_id}'");
if(!$find_game->num_rows)
  header('Location: ../homepage.php');
$game = $find_game->fetch_object();
$game_name = $game->name;

require '../../db/connect.php';
$find_review = $db->query("SELECT * FROM Game_Reviews WHERE game_name = '{$game_name}' AND reviewer = '{$reviewer}'");
if(!$find_review->num_rows)
  header('Location: ../homepage.php');
$review = $find_review->fetch_object();

if(isset($_POST["delete"]))
{
  $comment_id = $_POST["comment_id"];
  require '../../db/connect.php';

  $delete_comment = $db->prepare("CALL delete_game_review_comment(?,?,?,?)");
  $delete_comment->bind_param('sssi', $my_email, $game_name, $reviewer, $comment_id);
  $delete_comment->execute();
}

if(isset($_POST["send"]) && isset($_POST["comment"]) &&!empty(trim($_POST["comment"])))
{
  require '../../db/connect.php';
  $insert_comment = $db->prepare("CALL add_game_review_comment(?,?,?,?)");
  $insert_comment->bind_param('ssss', $my_email, $game_name, $reviewer, trim($_POST["comment"]));
  $insert_comment->execute();
}
require '../../db/connect.php';
$comments = $db->query("SELECT * FROM Game_Review_Comments 
  WHERE reviewed_game= '{$game_name}' AND  reviewer = '{$reviewer}'");
?>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" href="../../images/basic/web_icon.png">
    <title>Game World | Game Review Zone</title>

    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../members/profile.css" rel="stylesheet">
    <link href="game_reviews.css" rel="stylesheet">
  </head>

  <body>
  <!--Navigation Bar-->
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
                <li><a href=<?php echo 'profile.php?email=',$my_email; ?>>View profile</a></li>
                <?php if($_SESSION['membership'] == "Normal User") {?>
                <li><a href="../members/message.php">View messages</a></li>
                <li><a href="../members/actions/friend_requests.php">View fiend requests</a></li> <?php } ?>                
                <li role="separator" class="divider"></li>
                <li><a href="actions/update_account.php">Update account</a></li>
                <li><a href="../logout.php">Logout</a></li>                
              </ul>
            </li>
          </ul>    
          <form class="navbar-form navbar-right" method="post" action="../members/actions/search.php">
            <input type="text" class="form-control" name = "name" placeholder="Search...">
          </form>
      </div>
    </nav>

    <!--Review Zone-->  
    <div class="review">
      <div class="review-header">
        <h1 class="review-title">Review on 
        <a href=<?php echo 'game_info.php?game_id=' . $game_id;?>><?php echo $game_name;?></a></h1>
        <hr>
        <h3 class="reviewer">
          By: <a href=<?php echo "../members/profile.php?email=" . $reviewer;?>><?php echo $reviewer;?></a></h3>
        <h4 class="review-time"><?php echo date("h:m:s | d-m-Y",strtotime($review->date_time));?></h4>  
      </div>
      
      <div class="review-body">
          <p><?php echo $review->content; ?></p>
      </div>

      <div class="review-comments">
        
      <?php 
        while($c = $comments->fetch_object())
        {
      ?>
        <div class="review-comment">
          <p class="commenter">
            <a href=<?php echo "../members/profile.php?email=" . $c->commenter; ?>><?php echo $c->commenter; ?></a></p>
          <?php if($c->commenter == $my_email) { ?>
          <form method="post" action="" style="display:inline">
          <input type="text" name="comment_id" value=<?php echo $c->comment_id; ?> hidden>
          <input type="submit" name="delete" value= "delete" id="delete-comment">
            
          </form>
          
          <?php } ?>
          <p style="float: right"><?php echo date("h:m:s | d-m-Y", strtotime($c->date_time)); ?></p>
          <p ><?php echo $c->content; ?></p>
          
        </div>

      <?php } ?>       
      
      <div class="review-comment">
        <form method="post" action="">
          <textarea name="comment" id="new-comment" placeholder="share your opinion..."></textarea>
          <input type="submit" name="send" value="Comment" id="send">
        </form>
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
  </body>
</html>
