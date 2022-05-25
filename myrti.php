<?php
session_start();
if(!isset($_SESSION['usr_name'])) {
header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="https://overpass-30e2.kxcdn.com/overpass.css"/>
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/jquery.qtip.css" />
<link rel="stylesheet" href="css/bootstrap-slider.css" type="text/css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
<link href="css/bootstrap-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>

<script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>  
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" >
$(function() {
    $('.confirm').click(function(e) {
        e.preventDefault();
        if (window.confirm("Are you sure you want to delete this item ?")) {
            location.href = this.href;
        }
    });
});
</script>


</head>


<body onload="myFunction()">
<div id="loader"></div>

<div style="display:none;" id="myDiv" class="animate-bottom">

<nav id="top" class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php"><img src="images/innovate.png">  Assessment Tool</a>

		</div>
		<div class="collapse navbar-collapse" id="navbar1">
			<ul class="nav navbar-nav navbar-right">
				<?php if (isset($_SESSION['usr_id'])) { ?>
				<li><a href="myrti.php">My RTI</a></li>
				<li><a href="assess.php">Run Assessment</a></li>
				<li><a href="#">Signed in as <?php echo $_SESSION['usr_name']; ?></a></li>
				<li><a href="logout.php">Log Out</a></li>
				<?php } else { ?>
				<li><a href="register.php">Register</a></li>
				<li><a href="login.php">Login</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>

    <div class="container">
 
<?php
if(isset($_SESSION['usr_id'])) {
include 'dbconnect.php';
$userId = $_SESSION['usr_id'];
$userName = $_SESSION['usr_name'];

?>
    <div class="container">
<!-- Button to Open the Modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  What's New?
</button>

<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">New Features in RTI</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
		<p>New feature to delete assessments</p>		
		<p>New feature to edit customer details such as name, project and comments.</p>		
		<p>Graphs depicting Lines of Business for each users</p>
		<p>Toggle to show if the assessment was used for demo purposes.  Demo data isn't included in comparison graphs</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>    
    
    
<h3>Completed Assessments for <?php print $userName; ?> </h3>
<table  class="bordered" id="assessmentTable">
    <thead>
    <tr>
        <th>Client Name</th>  
        <th>Project/Team</th>  
        <th>Email Address</th>      
        <th>Country</th>
        <th>Line of Business</th>
        <th>Comments</th>
        <th>Timestamp</th>
        <th>Customer Data</th>
        <th>Link to Output</th>
        <th>Edit Details</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
<?php
connectDB();
$qq = "SELECT * FROM data WHERE user='".$userName."'";
$res = mysqli_query($db, $qq);
while ($row = $res->fetch_assoc()) {
	if ($row['demo'] == "on") {
	$demoData = "<img src=images/cross.png>";
	} else {
	$demoData = "<img src=images/tick.png>";
	}

if ($row['project'] == "") {
  $projDetails = " - ";
} else {
  $projDetails = $row['project'];
}

if ($row['comments'] == "") {
  $commentsDetails = " - ";
} else {
  $commentsDetails = $row['comments'];
}

	
print "<tr><td>" . $row['client'] . "</td><td>"  . $projDetails .  "</td><td>" . $row['rhEmail'] . "</td><td>" . $row['country'] . "</td><td>" . $row['lob'] . "</td><td>" . $commentsDetails . "</td><td>" . $row['date'] . "</td><td>" . $demoData . "</td><td><a href=results.php?hash=" . $row['hash'] . ">Link</a></td><td><a href=edit.php?hash=" . $row['hash'] . "><img src=images/edit.png></td><td><a class=\"confirm\"  href=delete.php?hash=" . $row['hash'] . " ><img src=images/delete.png></a></td></tr>";
}

#$q1 = "select lob, count(*) as total from data where demo <> 'on' group by lob order by total desc ;";
#$sth = mysqli_query($db, $q1);

?>
<tbody>
</table>



<script type="text/javascript" >
// Select all links with hashes
$('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function(event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
      && 
      location.hostname == this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000, function() {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) { // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          };
        });
      }
    }
  });
</script>

 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
 
 ['class Name','Students'],
 <?php 
 			$query = "select lob, count(*) as total from data where user=\"$userName\" and demo <> 'on' group by lob order by total desc ;";
			 $exec = mysqli_query($db,$query);
			 while($row = mysqli_fetch_array($exec)){
 
			 echo "['".$row['lob']."',".$row['total']."],";
			 }
			 ?> 
 
 ]);
 
 var options = {
 title: 'Breakdown by Line of Business',
  pieHole: 0.5,
          pieSliceTextStyle: {
            color: 'black',
          },
          legend: 'XXXX'
 };
 var chart = new google.visualization.PieChart(document.getElementById("columnchart12"));
 chart.draw(data,options);
 }
	
    </script>
    
 <div id="columnchart12"></div>

      </div>
    </div> <!-- /container -->
<?php    }
####  End of Logged on bit ######
?>

<script>
$(document).ready( function () {
    $('#assessmentTable').DataTable();
} );
</script> 
    <script>
      function openForm() {
        document.getElementById("popupForm").style.display="block";
      }
      
      function closeForm() {
        document.getElementById("popupForm").style.display="none";
      }
    </script>

<script>
var myVar;

function myFunction() {
  myVar = setTimeout(showPage, 200);
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
}
</script>    
    
</body>
</html>
