<?php 
if(!isset($_SESSION))
     session_start();
if(!isset($_SESSION['email']))
  header('Location: ../homepage.php');
else
  $my_email = $_SESSION['email'];

if(isset($_GET["game_id"]))
  $game_id = trim($_GET["game_id"]);
else
   header("Location: ../members/arena.php");
 
require '../../functions/security.php';
require '../../db/connect.php';

$search_game = $db->query("SELECT * FROM Games WHERE game_no = '{$game_id}'");
if(!$search_game->num_rows)
	header("Location: ../members/arena.php");

$game = $search_game->fetch_object();



if(isset($_POST["send"]) && isset($_POST["review"]) && !empty(trim($_POST["review"])))
{
  
  $review = trim($_POST["review"]);
  require '../../db/connect.php';
  
  $insert_review = $db->prepare("CALL add_game_review(?,?,?)");
  $insert_review->bind_param('sss', $my_email, escape($game->name), $review);

  $insert_review->execute();

}


if(isset($_POST["rate"]))
{
  if(empty($_POST["rating-l"]))
    $r1 = 0;
  else
    $r1 = $_POST["rating-l"];

  if(empty($_POST["rating-g"]))
    $r2 = 0;
  else
    $r2 = $_POST["rating-g"];

  if(empty($_POST["rating-u"]))
    $r3 = 0;
  else
    $r3 = $_POST["rating-u"];

  if(empty($_POST["rating-i"]))
    $r4 = 0;
  else
    $r4 = $_POST["rating-i"];

  require '../../db/connect.php';
  $rate_game = $db->prepare("CALL rate_game(?,?,?,?,?,?)");
  $rate_game->bind_param('ssiiii',$my_email, escape($game->name), $r1, $r2, $r3, $r4);
  $rate_game->execute();  

  require '../../db/connect.php';

  $game = $db->query("SELECT * FROM Games WHERE game_no = '{$game_id}'");
  $game = $game->fetch_object();
}

if(isset($_POST["recommend"]) && isset($_POST["email"]) && !empty(trim($_POST["email"])))
{
  require '../../db/connect.php'; 
  $recommend_to = trim($_POST["email"]);
  if($my_email != $recommend_to)
  {
    $recommend = $db->prepare("CALL recommend_game(?,?,?)");
    $recommend->bind_param('sss', $my_email, $recommend_to, escape($game->name)); 
    $recommend->execute();
  }
  
}

require '../../db/connect.php';

$game_name = escape($game->name);

$check_rate = $db->query("SELECT * FROM Rate_Games WHERE rated_game = '{$game_name}' AND rating_member = '{$my_email}'");
if($check_rate->num_rows)
  $rated = 1;
else
  $rated = 0;

$game_type = null;
require '../../db/connect.php';
$check_type = $db->query("SELECT * FROM Action_Games WHERE name = '{$game_name}'");
if($check_type->num_rows)
{
  $game_type = 'Action';
  $sub_genre = $check_type->fetch_object();
  if($sub_genre->sub_genre)
    $game_type =  $game_type . ' (' . $sub_genre->sub_genre . ')';
}	
else
{
	require '../../db/connect.php';
	$check_type = $db->query("SELECT * FROM Sport_Games WHERE name = '{$game_name}'");
	if($check_type->num_rows)
	{
    $game_type = 'Sport';
    $sport_type = $check_type->fetch_object();
    if($sport_type->type)
      $game_type =  $game_type . ' (' . $sport_type->type . ')';
  }	
	else
	{
		require '../../db/connect.php';
		$check_type = $db->query("SELECT * FROM Strategy_Games WHERE name = '{$game_name}'");
		if($check_type->num_rows)
		{
      $game_type = 'Strategy';
      $is_realtime = $check_type->fetch_object();
      if($is_realtime->is_realtime)
        $game_type =  $game_type . ' (realtime)';
    }
		else
		{
			require '../../db/connect.php';
			$check_type = $db->query("SELECT * FROM Role_Playing_Games WHERE name = '{$game_name}'");
			if($check_type->num_rows)
			{
        $game_type = 'Role Playig';
        $rp_game = $check_type->fetch_object();
        if($rp_game->isPVP)
          $game_type = $game_type . ', player vs. player';
        if($rp_game->storyline)
          $game_type = $game_type . ', ' . $rp_game->storyline;
      }
		}

	}
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
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    
    <link rel="icon" href="../../images/basic/web_icon.png">
    <title>Game World | <?php echo $game_name;?></title>

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
    <link href="../members/profile.css" rel="stylesheet">
    <link href="game_info.css" rel="stylesheet">

    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    


	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="viewer/js/slimbox2.js"></script>
	<link rel="stylesheet" href="viewer/css/slimbox2.css" type="text/css" media="screen" />
	<style type="text/css">
		.viewer body {
			background-color: #fff;
			font-family: arial, helvetica, sans-serif;
			color: #000;
		}
		.viewer h1, .viewer p {
			text-align: center;
		}
		.viewer p {
			margin-top: 100px;
		}
		.viewer a {
			font-weight: bold;
			color: #f00;
		}
	</style>



  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link rel="stylesheet" href="../../dist/themes/fontawesome-stars.css">

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
        <h1 style="padding: 5px 15px"> <?php echo $game_name;?>
         </h1>
        
       <div>
       	<h3>About the Game</h3>
       	
       		<div class="row row-panel review">
        	<p>Game Type: <?php echo $game_type;?></p>
        	<p>Release Date: <?php echo date('d-m-Y', strtotime($game->release_date));?></p>
          	<p>Age Limit: <?php echo $game->age_limit;?></p>
          	
          	<p style="display:inline-block">Rating: <?php echo $game->rating;?></p>

            <?php 

              if(!$rated)
              {

            ?>
                <button class="rate" style="display:inline-block; font-size:24px; ">Rate!</button>
            <?php } ?>
          	<p>Development Team: <a href=<?php echo "../members/profile.php?email=".$game->team;?>><?php echo $game->team;?></a></p>

            <?php 

              if($_SESSION["membership"] == "Normal User")
              {
                require '../../db/connect.php';
                $recommendations = $db->query("SELECT * FROM Recommend_Games
                  WHERE user2 = '{$my_email}' AND recommended_game = '{$game_name}'
                ");
                if($recommendations->num_rows){
            ?>  

              <p><?php echo $recommendations->num_rows; ?> member(s) recommended this game for you</p>
              <?php } ?>
              Interesting? then 
                <button class="recommend" style="font-size:24px;">recommend</button>
                it to others!
            <?php } ?>
        
      </div>
       </div>
       
       <div>
       	<h3>Screenshots</h3>
       	<div class="row viewer row-panel review">
       	<div class="container-outer">
   <div class="container-inner">
      
   
       	<?php 

       		require '../../db/connect.php';
       		
       		$result = $db->query("CALL view_game_screenshots('{$game_name}')");
       		if(!$result->num_rows)
       		{

       		}
       		else
       		{
       			while ($s = $result->fetch_assoc()) { ?>
       								
       			
       				<a href=<?php echo $game_id . '/screenshots/'. $s["screenshot"]; ?> rel="lightbox">

       				<img src=<?php echo $game_id . '/screenshots/'. $s["screenshot"]; ?>>

       				</a>

       			<?php }

       		}
       	?>
       		
   </div>
</div>	
	<div>
		


	</div>
        
      </div>
       </div> 
      
       <div>
       	<h3>Videos</h3>
       	<div class="row container row-panel review" >
		<video width="800" height="450" controls style="display:inline-block">
			  <source src=""  type="video/mp4">
			  
		</video>

		
		<div class="video-container">
			
				<?php 

       		require '../../db/connect.php';
       		
       		$result = $db->query("CALL view_game_videos('{$game_name}')");
       		if(!$result->num_rows)
       		{

       		}
       		else
       		{
       			$c = 1;
       			while ($s = $result->fetch_assoc()) { ?>
       								
       				
       				<button class="btt" value=<?php echo $game_id . '/videos/'. $s["video"]; ?>> 

       				<?php echo 'Video'. $c; ?>

       				</button>

       			<?php $c++;}

       		}
       	?>



		</div>
</div>
	</div>	
   
       	 <div>
       	<h3>Game Reviews</h3>
       	
       		<div class="row">
          
          <?php
            require '../../db/connect.php';
            
            $result = $db->query("CALL view_game_reviews('{$game_name}')");
            $reviewer_wrote = 0;
            while($r = $result->fetch_object()) {

                if($r->reviewer == $my_email)
                  $reviewer_wrote = 1;
                $title = $r->content;

                if(strlen($title) > 35)
                  $title = substr($title, 0, 35) . "...";
              ?>
              <div class="review">
                
                <h4>By: <?php echo $r->reviewer; ?></h4>
                <a href=<?php echo "game_reviews.php?game_id=" . $game->game_no . '&reviewer=' . $r->reviewer; ?>>
                <p style="padding-left:20px;"><?php echo $title; ?></p></a>
              </div>
                

              <?php
            }

          ?>
          <?php 
            if($_SESSION["membership"] == "Verified Reviewer" && !$reviewer_wrote){ ?>

            <button id="add-review">Add Review</button>
              
              
              <div class="new-review-area">
                  <form method="post" action="">
                <textarea name="review" id="new-review" placeholder="Share your opinion..."></textarea>
                <input type="submit" name="send" value="Add" id="send">
              </form>
              
              </div>

<?php
            }

          ?>
      </div>
       </div>
     

       <div class="rating">
       <form method="post" action="">
          <div class="stars stars-example-fontawesome">
            <span class="rating-title">Graphics</span>  
            <select class="example-fontawesome" name="rating-g">
                    <option value=""></option>
                    <option value="1"></option>
                    <option value="2"></option>
                    <option value="3"></option>
                    <option value="4"></option>
                    <option value="5"></option>
                    <option value="6"></option>
                    <option value="7"></option>
                    <option value="8"></option>
                    <option value="9"></option>
                    <option value="10"></option>
                  </select>
             
          </div>
       

       <div class="stars stars-example-fontawesome">
            <span class="rating-title">Interactivity</span>   
            <select class="example-fontawesome" name="rating-i">
                    <option value=""></option>
                    <option value="1"></option>
                    <option value="2"></option>
                    <option value="3"></option>
                    <option value="4"></option>
                    <option value="5"></option>
                    <option value="6"></option>
                    <option value="7"></option>
                    <option value="8"></option>
                    <option value="9"></option>
                    <option value="10"></option>
                  </select>
            
          </div>

           <div class="stars stars-example-fontawesome">
            <span class="rating-title">Uniqueness</span>   
            <select class="example-fontawesome" name="rating-u">
                    <option value=""></option>
                    <option value="1"></option>
                    <option value="2"></option>
                    <option value="3"></option>
                    <option value="4"></option>
                    <option value="5"></option>
                    <option value="6"></option>
                    <option value="7"></option>
                    <option value="8"></option>
                    <option value="9"></option>
                    <option value="10"></option>
                  </select>
            
          </div>

           <div class="stars stars-example-fontawesome">
            <span class="rating-title">Level Design</span> 
            <select class="example-fontawesome" name="rating-l">
                    <option value=""></option>
                    <option value="1"></option>
                    <option value="2"></option>
                    <option value="3"></option>
                    <option value="4"></option>
                    <option value="5"></option>
                    <option value="6"></option>
                    <option value="7"></option>
                    <option value="8"></option>
                    <option value="9"></option>
                    <option value="10"></option>
                  </select>
              
          </div>
          <input type="submit" value="Rate!" name="rate" class="rate">
          </form>
          </div>   

         <div class="recommendation">
          <form method="post" action="">
          <p style="font-size:30px; color: white; text-shadow:0px 0px 3px black">Whom do you want to recommend this game to?</p>
          <input type="text" name="email" id="email" placeholder="Email"/>
           <input type="submit" value="Recommend" name="recommend" class="recommend">
          </form>
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
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

    <script src="../../_includes/js/jquery.js"></script>


    <script>window.jQuery || document.write('<script src="../../_includes/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

    
    <script src="../../_includes/js/examples.js"></script>

    <script src="../../dist/jquery.barrating.min.js"></script>
   
    <script>
    $(document).ready(function(){

    	$(".btt").click(function(){

    		
    		$("source").attr("src", $(this).attr("value"));
    		$("video")[0].load();

    	});

      $("h3").click(function(){
        
        $(this).next().slideToggle("slow");

      });

      $("#add-review").click(function(){
        $(this).slideToggle("slow");
        $(".new-review-area").slideToggle("slow");
        $("#send").fadeIn(700);
      });

      $("button.rate").click(function(){

        $(".rating").fadeIn();

      });

      $("button.recommend").click(function(){

        $(".recommendation").fadeIn();

      });

    });

    $(document).keyup(function(e) {
     if (e.keyCode == 27) { 
        $(".rating").fadeOut();   
        $(".recommendation").fadeOut();     
    }
});
    </script>
  </body>
</html>