<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Assessment Tool</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" >
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3.css"/>
<link rel="stylesheet" type="text/css" href="https://overpass-30e2.kxcdn.com/overpass.css"/>
<link rel="stylesheet" href="css/style.css" />
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
  
    <script>
  $( function() {
    $( "input" ).checkboxradio();
  } );
  </script>
     
</head>
<body>
<?php # include_once("analyticstracking.php") ?>  
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php"><img src="images/innovate.png">  Assessment Tool</a>
			<div id="smallVersion">v2.0</div>
			</div>
		<div class="collapse navbar-collapse" id="navbar1">
			<ul class="nav navbar-nav navbar-right">
				<?php if (isset($_SESSION['usr_id'])) { ?>
				<li><a href="myrti.php">MyRTI</a></li>
				<li><a href="assess.php">Run Assessment</a></li>
				<li><p class="navbar-text">Signed in as <?php echo $_SESSION['usr_name']; ?></p></li>
				<li><a href="logout.php">Log Out</a></li>
				<li><a href="blog">Blog</a></li>
				<?php } else { ?>
				<li><a href="register.php">Register</a></li>
				<li><a href="login.php">Login</a></li>
				<li><a href="blog">Blog</a></li>

				<?php } ?>

			</ul>
		</div>
	</div>
</nav>

<?php
if(isset($_SESSION['usr_id'])) {
include 'dbconnect.php';
$userId = $_SESSION['usr_id'];

?>
    <div class="container">

      </div>


    </div> <!-- /container -->
<?php    }
####  End of Logged on bit ######
?>

  <h3>Welcome to the RH Assessment Tool</h3>

<p class="blackWelcomeText">
RH Assessment Tool is a web based, modular tool for assessing client in a phased approach. You can run each phase individually or using Discovery assessment, discover the best target areas for the customer and run a Preliminary Technical Validation straight after.</p>

<p class="blackWelcomeText">
The possible assessment phases are
</p>
 <ul>
<li>
       Discovery Assessment
</li>
<li>
       Preliminary Technical Validation
</li>
</ul>
<!-- <div class="leftTable">
<table>
<tr><td><img src="images/automation.png"></td><td>Automation</td></tr>
<tr><td><img src="images/wayOfWorking.png"></td><td>Way of Working</td></tr>
<tr><td><img src="images/architecture.png"></td><td>Architecture</td></tr>
<tr><td><img src="images/visionLeadership.png"></td><td>Vision and Leadership</td></tr>
<tr><td><img src="images/environment2.png"></td><td>Environment</td></tr>
</table>
</div>
 --><br>

<!-- <p class="blackWelcomeText">
The assessment is mainly based on the integration, processes and methods used by both development and operations teams. To provide a more holistic overview, include members of other teams such as security, testing and business owners.</p>
-->

<?php

function printAssessmentPhases($title,$area) {
  $string = file_get_contents("assessment_phases.json");
  $json = json_decode($string, true);
  $i=1;
//  $qnum = $json[$area]['qnum'];
//  $dim = sizeof($json[$area]['Assessment'],0);
//  while( $i <= $dim) {
  $ii=1;
  print '<br>';
//  print '<h4 class="headerCentered">' . $json[$area]['Assessment'][$i]['name'] . '</h4>';
  print '<br>';
  while( $ii < 3) {

        if($ii % 2 == 0){
        print '<div class="divTableCell">';
        } else {
        print '<div class="dark">';
        }
        $det = $ii . '-details';
        $sum = $ii . '-summary';
        $ref = $ii . '-href';
print '<br>';

print '<b><ul><a href="' . $json[$area]['Assessment'][$i]['phases'][$ref] . '"><img src="images/innovate.png">' . $json[$area]['Assessment'][$i]['phases'][$ii] . '</a></ul></b>';
print "<details>";
print '<summary>' . $json[$area]['Assessment'][$i]['phases'][$sum] . "</summary>";
print '<div class="detailsPane">' . $json[$area]['Assessment'][$i]['phases'][$det] . '</div>';
print "</details>";
print "</div>";
$ii++;
}
};

printAssessmentPhases("Overall Discovery Assessment","PTA");

?>



</body>
</html>

