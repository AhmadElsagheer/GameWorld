<?php

if(!isset($_SESSION)) 
     session_start();
if(!isset($_SESSION['email']))
  header('Location: ../homepage.php');
else
  $my_email = $_SESSION['email'];
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
    <title>Game World | Arena</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="profile.css" rel="stylesheet">
    <link href="arena.css" rel="stylesheet">

    
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

    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active" style="background-color: rgba(255, 255, 255, 0)">
          <img class="first-slide" src="../../images/homepage/arena_1.jpg" alt="First slide">
          <div class="container" >
            <div class="carousel-caption">
              <h1>Let the challenge begin!</h1>
              <p>Discover new games, play them and challenge the top gamers.</p>
              <p><a class="btn btn-lg btn-primary" href="../games/games_arena.php" role="button">Find Games</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="second-slide" src="../../images/homepage/arena_2.jpg" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>On fire discussions.</h1>
              <p>Follow gaming conferences, write your own review and comment on others.</p>
              <p><a class="btn btn-lg btn-primary" href="../construction.php" role="button">Find Gaming Conferences</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="../../images/homepage/arena_3.jpg" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Get Invovled.</h1>
              <p>Communicate with other network members and discuss different gaming topics.</p>
              <p><a class="btn btn-lg btn-primary" href="../construction.php" role="button">Find Communities</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
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
  </body>
</html>
