<!DOCTYPE html>
<html>
<?php
date_default_timezone_set("Europe/London");
## Connect to the Database 
include 'dbconnect.php';
connectDB();

# Retrieve the data
$hash = $_REQUEST['hash'];
$qq = "SELECT * FROM maturity_scoring WHERE hash='".mysqli_real_escape_string($db, $hash)."'";
$qq_data = "SELECT * FROM data WHERE hash='".mysqli_real_escape_string($db, $hash)."'";
#print $qq;
$res = mysqli_query($db, $qq);
$res_data = mysqli_query($db, $qq_data);
$data_array = mysqli_fetch_assoc($res);
$data_array_info = mysqli_fetch_assoc($res_data);

#global $data_array;
#var_dump($data_array);
if (empty($data_array)) {
print "<h2> Results not found </h2>";
exit;
}
$ops_arr = $dev_arr = $opsRound_arr = $devRound_arr = array();
$share = $data_array_info['share'];
$lob = $data_array_info['lob'];

function getRating($num) {
#$roundedNum = round($num,0);
$roundedNum = floor($num);
#print "Rounded: $roundedNum <br>";
switch ($roundedNum) {
	case "1":
		$rating = "Rudimentary";
		$ratingRank = "<b>Rudimentary</b>: ";
#		$ratingDescription = $ratingRank . "Governance practices are either non-existent or in the very early stages of development";
		break;
	case "2":
		$rating = "Developing";
		$ratingRank = "<b>Developing</b>: ";
#		$ratingDescription = $ratingRank . "Potential shortfalls in governance practices have been identified and initial steps have been taken to rectify them. There is significant room for improvement.";
		break;
	case "3":
		$rating = "Acceptable";
		$ratingRank = "<b>Acceptable</b>: ";
#		$ratingDescription = $ratingRank . "The minimum governance practices are in place. There is still room for improvement.";
		break;
	case "4":
		$rating = "Advanced";
		$ratingRank = "<b>Advanced</b>: ";
#		$ratingDescription = $ratingRank . "Governance practices are in place and exceed performance and compliance requirements. Only minor improvements are required to achieve and be recognised as leading practices.";
		break;
	case "5":
		$rating = "Leading";
		$ratingRank = "<b>Leading</b>: ";
#		$ratingDescription = $ratingRank .  "All processes and practices are recognised by others to be of the highest standard";
		break;
}
return $rating;
}

$string = file_get_contents("questionsV2.json");
$json = json_decode($string, true);

$string2 = file_get_contents("comments.json");
$comments = json_decode($string2, true);
?>
<head>
    <script src="js/Chart.bundle.js"></script>
    <script src="js/utils.js"></script>
    <script src="js/raphael-2.1.4.min.js"></script>
    <script src="js/justgage.js"></script>
    <title> Assessment Tool</title>
<link rel="stylesheet" type="text/css" href="css/overpass.css"/>

    <link href="css/rhbar.css" media="screen" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>    
	 <link rel="stylesheet" href="css/style.css">

   <script>
  $( function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "blind",
        duration: 1000
      }
    });
 
    $( "#opener" ).on( "click", function() {
      $( "#dialog" ).dialog( "open" );
    });
  } );
  </script>

  <script>
  $( function() {
    $( "#workshop-dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "drop",
        duration: 1000
      },
      minWidth: 400
    });
 
    $("#workshop-opener" ).on( "click", function() {
      $("#workshop-dialog" ).dialog( "open" );
    });

  } );
  </script>

  <script>
  $( function() {
    $( "#priorities-dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "drop",
        duration: 1000
      },
      minWidth: 800
    });
 
    $("#priorities-opener" ).on( "click", function() {
      $("#priorities-dialog" ).dialog( "open" );
    });

  } );
  </script>

  <script>
  $( function() {
    $( "#average-dialog-dev" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "drop",
        duration: 1000
      },
      minWidth: 1000
    });
 
    $( "#average-opener-dev" ).on( "click", function() {
      $( "#average-dialog-dev" ).dialog( "open" );
    });
  } );
  </script>

  <script>
  $( function() {
    $( "#average-dialog-dev-lob" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "drop",
        duration: 1000
      },
      minWidth: 1000
    });
 
    $( "#average-opener-dev-lob" ).on( "click", function() {
      $( "#average-dialog-dev-lob" ).dialog( "open" );
    });
  } );
  </script>

  <script>
  $( function() {
    $( "#average-dialog-ops-lob" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "drop",
        duration: 1000
      },
      minWidth: 1000
    });
 
    $( "#average-opener-ops-lob" ).on( "click", function() {
      $( "#average-dialog-ops-lob" ).dialog( "open" );
    });
  } );
  </script>

  <script>
  $( function() {
    $( "#average-dialog-ops" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "drop",
        duration: 1000
      },
      minWidth: 1000
    });
 
    $( "#average-opener-ops" ).on( "click", function() {
      $( "#average-dialog-ops" ).dialog( "open" );
    });
  } );
  </script>

 

<script>
$(document).ready(function() {
  $(function() {
    console.log('false');
    $( "#dialog" ).dialog({
        autoOpen: false,
        title: 'Email PDF'
    });
  });

  $("button").click(function(){
    console.log("click");
//        $(this).hide();
        $( "#dialog" ).dialog('open');
    });
}); 
</script>
  <script>
  $( function() {
    $( "#dialog" ).dialog();
  } );
  </script>
  
    <script>
  $( function() {
    $( document ).tooltip();
  } );
  </script>
</head>

<body>
  <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
<?php  

#var_dump($data_array);

foreach( $data_array as $var => $value )
    {
    	if($var=='date') continue;
      if(substr($var[0],0,1) == "maturity") { $ops_arr[]=$value; $opsRound_arr[] = round($value);  };
      if(substr($var[0],0,1) == "d") { $dev_arr[]=$value; $devRound_arr[] = round($value);  };
    } 
     
 ?>
      <div id="wrapper">
      <header>

      <center>
      <h2> Assessment for <?php echo $data_array_info['client']; 
		if ($data_array_info['project'] != "") {
			print " (" . $data_array_info['project'] . ")";		
		}      
      ?></h2>
      </center>
      </header>
      
<div id="content">       
    <div style="width:90%">
        <canvas id="canvas"></canvas>
    </div>
        <script>

    var customerName = '<?php echo addslashes($data_array_info['client']); ?>'
    var customerNameNoSpaces = customerName.replace(/\s+/, "");


function checkVal(inNo) {
	if (inNo == "0") {
		var outNo = "0.01";
	} else {
	   var outNo = inNo;	
	}
	return outNo
}

    var maturity_architecture_1 = checkVal(<?php echo $data_array['maturity_architecture_1'] ?>)
    var maturity_architecture_2 = checkVal(<?php echo $data_array['maturity_architecture_2'] ?>)
    var maturity_architecture_3 = checkVal(<?php echo $data_array['maturity_architecture_3'] ?>)
    var maturity_architecture_4 = checkVal(<?php echo $data_array['maturity_architecture_4'] ?>)
    
    var totalDev = maturity_architecture_1 + maturity_architecture_2 + maturity_architecture_3 + maturity_architecture_4 

    var maturity_automation_1 = checkVal(<?php echo $data_array['maturity_automation_1'] ?>)
    var maturity_automation_2 = checkVal(<?php echo $data_array['maturity_automation_2'] ?>)
    var maturity_automation_3 = checkVal(<?php echo $data_array['maturity_automation_3'] ?>)
    var maturity_automation_4 = checkVal(<?php echo $data_array['maturity_automation_4'] ?>)

    var totalOps = maturity_automation_1 + maturity_automation_2 + maturity_automation_3 + maturity_automation_4 

    var chartTitle = "DevOps Chart - " + customerName
    var overall1 = (maturity_architecture_1+maturity_automation_1)/2;
    var overall2 = (maturity_architecture_2+maturity_automation_2)/2;
    var overall3 = (maturity_architecture_3+maturity_automation_3)/2;
    var overall4 = (maturity_architecture_4+maturity_automation_4)/2;
    
    var randomScalingFactor = function() {
        return Math.round(Math.random() * 4);
    };

    var color = Chart.helpers.color;
    var config = {
        type: 'radar',
        data: {
            labels: ["Automation", "Way of Working", "Architecture", "Vision and Leadership", "Environment"],
            datasets: [{
                label: "Architecture",
                backgroundColor: color(window.chartColors.red).alpha(0.2).rgbString(),
                borderColor: window.chartColors.red,
                pointBackgroundColor: window.chartColors.red,
                data: [maturity_architecture_1,maturity_architecture_2,maturity_architecture_3,maturity_architecture_4]
            }, {
                label: "Automation",
                backgroundColor: color(window.chartColors.blue).alpha(0.2).rgbString(),
                borderColor: window.chartColors.blue,
                pointBackgroundColor: window.chartColors.blue,
                data: [maturity_automation_1,maturity_automation_2,maturity_automation_3,maturity_automation_4]

            }]
        },
        options: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: chartTitle
            },
            scale: {
            
              ticks: {
                beginAtZero: true,
                max: 5,
                min: 0
              }
            },

    }
 }
    window.onload = function() {
        window.myRadar = new Chart(document.getElementById("canvas"), config);

var ctx = document.getElementById("myChartDev").getContext("2d");
var data = {
  labels: ["Automation", "Way of Working", "Architecture", "Vision and Leadership", "Environment"],
  datasets: [{
    label: customerName,
    backgroundColor: 'green',
    data: [maturity_architecture_1, maturity_architecture_2, maturity_architecture_3, maturity_architecture_4]
  }, {
    label: "Average",
    backgroundColor: "orange",
    data: <?php 
 #   $qq = "select avg(maturity_architecture_1) as maturity_architecture_1,avg(maturity_architecture_2) as maturity_architecture_2, avg(maturity_architecture_3) as maturity_architecture_3, avg(maturity_architecture_4) as maturity_architecture_4 from data;";    
    $qq = "select ROUND(avg(maturity_architecture_1),2) as maturity_architecture_1, ROUND(avg(maturity_architecture_2),2) as maturity_architecture_2, ROUND(avg(maturity_architecture_3),2) as maturity_architecture_3, ROUND(avg(maturity_architecture_4),2) as maturity_architecture_4 from maturity_scoring;";    
    $res = mysqli_query($GLOBALS["___mysqli_ston"], $qq);
    $row = mysqli_fetch_array($res);
     echo "[" . $row[0] . "," . $row[1] . "," . $row[2] . "," . $row[3] . "," . $row[4] . "]"; 
     ?>
  },
  ]
};

var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: data,
  options: {
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
          max: 5
        }
      }]
    },

  }
});  		

var ctxLobDev = document.getElementById("myChartDevLob").getContext("2d");
var data = {
  labels: ["Automation", "Way of Working", "Architecture", "Vision and Leadership", "Environment"],
  datasets: [{
    label: customerName,
    backgroundColor: 'green',
    data: [maturity_architecture_1, maturity_architecture_2, maturity_architecture_3, maturity_architecture_4]
  }, {
    label: "Average",
    backgroundColor: "orange",
    data: <?php 
    $qq = "select ROUND(avg(maturity_architecture_1),2) as maturity_architecture_1, ROUND(avg(maturity_architecture_2),2) as maturity_architecture_2, ROUND(avg(maturity_architecture_3),2) as maturity_architecture_3, ROUND(avg(maturity_architecture_4),2) as maturity_architecture_4 from maturity_scoring;";    
    $res = mysqli_query($GLOBALS["___mysqli_ston"], $qq);
    $row = mysqli_fetch_array($res);
     echo "[" . $row[0] . "," . $row[1] . "," . $row[2] . "," . $row[3] . "," . $row[4] . "]"; 
     ?>
  },
  ]
};

var myBarChart = new Chart(ctxLobDev, {
  type: 'bar',
  data: data,
  options: {
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
          max: 5
        }
      }]
    },

  }
});  	

var ctxLobOps = document.getElementById("myChartDevOps").getContext("2d");
var data = {
  labels: ["Automation", "Way of Working", "Architecture", "Vision and Leadership", "Environment"],
  datasets: [{
    label: customerName,
    backgroundColor: 'green',
    data: [maturity_architecture_1, maturity_architecture_2, maturity_architecture_3, maturity_architecture_4]
  }, {
    label: "Average",
    backgroundColor: "orange",
    data: <?php 
    $qq = "select ROUND(avg(maturity_automation_1),2) as maturity_automation_1, ROUND(avg(maturity_automation_2),2) as maturity_automation_2, ROUND(avg(maturity_automation_3),2) as maturity_automation_3, ROUND(avg(maturity_automation_4),2) as maturity_automation_4 from maturity_scoring;";    
    $res = mysqli_query($GLOBALS["___mysqli_ston"], $qq);
    $row = mysqli_fetch_array($res);
     echo "[" . $row[0] . "," . $row[1] . "," . $row[2] . "," . $row[3] . "," . $row[4] . "]"; 
     ?>
  },
  ]
};

var myBarChart = new Chart(ctxLobOps, {
  type: 'bar',
  data: data,
  options: {
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
          max: 5
        }
      }]
    },

  }
});  	

var ctx2 = document.getElementById("myChartOps").getContext("2d");
var dataOps = {
  labels: ["Automation", "Way of Working", "Architecture", "Vision and Strategy", "Environment"],
  datasets: [{
    label: customerName,
    backgroundColor: 'green',
    data: [maturity_automation_1, maturity_automation_2, maturity_automation_3, maturity_automation_4]
  }, {
    label: "Average",
    backgroundColor: "orange",
    data: <?php 
#    $qq = "select avg(maturity_automation_1) as maturity_architecture_1,avg(maturity_automation_2) as maturity_architecture_2, avg(maturity_automation_3) as maturity_architecture_3, avg(maturity_automation_4) as maturity_architecture_4 from data;";    
    $qq = "select ROUND(avg(maturity_automation_1),2) as maturity_automation_1, ROUND(avg(maturity_automation_2),2) as maturity_automation_2, ROUND(avg(maturity_automation_3),2) as maturity_automation_3, ROUND(avg(maturity_automation_4),2) as maturity_automation_4 from maturity_scoring;";    
    $res = mysqli_query($GLOBALS["___mysqli_ston"], $qq);
    $row = mysqli_fetch_array($res);    
     echo "[" . $row[0] . "," . $row[1] . "," . $row[2] . "," . $row[3] . "," . $row[4] . "]"; 
     ?>
  },
  ]
};

var myBarChart2 = new Chart(ctx2, {
  type: 'bar',
  data: dataOps,
  options: {
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
          max: 5
        }
      }]
    },

  }
});  				
  		
};
             


    var colorNames = Object.keys(window.chartColors);
    </script>
<div class="centeredDiv">
<div class="w3-container">

</div>
</div>

<!-- <h4 class="centeredText">Overall Assessment</h4> -->


<?php
## Get an overall score by adding them all up
$totalScore = 0;

$areas = array('architecture','automation','environment','wow');
$area_count = sizeof($areas);
print '<table class="zebra">
<thead>
<th>Rating</th>
<th>Level</th>
</thead>
<tbody>
for($i = 1; $i < $area_count; $i++) {
$area = $areas[$i];
$areaScore = $data_array[' . $area . '_total];
$totalScore+= $data_array[' . $area .'_total];
<tr><td><b>' . $areaScore . ' out of 5</b></td><td class="' . strtolower(getRating($areaScore)) . '">' . getRating($areaScore) . '</td>
</tr>
}
<tr><td><b>' . $totalScore/5 . ' out of 5</b></td><td class="' . strtolower(getRating($totalScore/5)) . '">' . getRating($totalScore/5) . '</td>
</tr>
</tbody>
</table>';
#print "Total score: " . $totalScore/10;
?>

<?php print '<br>

<a target=_blank href=resultsOpen.php?hash=' . $hash . '><p class=centeredDiv>Detailed Version</p></a>'; ?>
</div>


<div id="rightcol">


<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Maturity Assessment</a></li>
    <li><a href="#tabs-6">Client Priorities And Goals</a></li>
<!--     <li><a href="#tabs-2">Actions</a></li> -->
<!--    <li><a href="#tabs-4">Notes</a></li>  -->
<li><a href="#tabs-5">Industry Comparisons</a></li>
<li><a href="#tabs-7">Past Assessments and History</a></li>
<li><a href="#tabs-3">Workshops</a></li>
      </ul>
      <div id="tabs-1">
  <table class="bordered">   
   <thead>
        <th>Area</th>        
        <th>Overall Maturity Scoring</th>
    </thead>
	<tbody> 
<tr><td></td><td></td></tr>
<?php
$areas = array(
	1 => "Overall"
);

function getActions($areaName,$type,$num,$comments){
## For example getActions("Automation","operations",1)
$actionField = round($num) . "-action";
print $comments[$areaName][$type][$actionField];

## TODO: Need to get ops and dev together with <td> etc
}

function printGauge($areaName,$num,$chartName,$arr) {
print '<tr><td><b>' . $areaName . '</b></td><td>
<div id="' . $chartName . '" style="height:60px;margin-left: auto;margin-right: auto;"></div>
	<p class="' . strtolower(getRating($arr)) . '">' . getRating($arr) . '</p>
</td>
</tr>';
print "</tr>";
}

printGauge("Architecture","3","arch",$data_array["architecture_total"]);
printGauge("Automation","1","automation",$data_array["automation_total"]);
printGauge("Way of Working","2","wow",$data_array["wow_total"]);
printGauge("Environment","5","env",$data_array["environment_total"]);



?>

</tbody>
</table>	


  <table class="bordered">
   <thead>
        <th>Architecture</th>
        <th>Overall Maturity Scoring</th>
    </thead>
        <tbody>
<tr><td></td><td></td></tr>
<?php
$areas = array(
        1 => "Overall"
);

printGauge("Application Modularity","3","maturity_architecture_1",$data_array["maturity_architecture_1"]);
printGauge("Infra and Network","1","maturity_architecture_2",$data_array["maturity_architecture_2"]);
printGauge("Technical Debt","2","maturity_architecture_3",$data_array["maturity_architecture_3"]);
printGauge("Observability and Monitoring","5","maturity_architecture_4",$data_array["maturity_architecture_4"]);


?>

</tbody>
</table>


  <table class="bordered">
   <thead>
        <th>Automation</th>
        <th>Overall Maturity Scoring</th>
    </thead>
        <tbody>
<tr><td></td><td></td></tr>
<?php
$areas = array(
        1 => "Overall"
);

printGauge("Source Control Managment","3","maturity_automation_1",$data_array["maturity_automation_1"]);
printGauge("Test Automation","1","maturity_automation_2",$data_array["maturity_automation_2"]);
printGauge("Release Managment","2","maturity_automation_3",$data_array["maturity_automation_3"]);
printGauge("Infrastructure Provisioning","5","maturity_automation_4",$data_array["maturity_automation_4"]);
?>

</tbody>
</table>

  <table class="bordered">
   <thead>
        <th>Way Of Working</th>
        <th>Overall Maturity Scoring</th>
    </thead>
        <tbody>
<tr><td></td><td></td></tr>
<?php
$areas = array(
        1 => "Overall"
);

printGauge("Agile","3","arch",$data_array["maturity_wow_1"]);
printGauge("Team Composition","1","automation",$data_array["maturity_wow_2"]);
printGauge("Managing Work in Progress","2","wow",$data_array["maturity_wow_3"]);
printGauge("Observability and Monitoring","5","env",$data_array["maturity_wow_4"]);


?>

</tbody>
</table>

  <table class="bordered">
   <thead>
        <th>Architecture</th>
        <th>Overall Maturity Scoring</th>
    </thead>
        <tbody>
<tr><td></td><td></td></tr>
<?php
$areas = array(
        1 => "Overall"
);

printGauge("Application Modularity","3","arch",$data_array["maturity_architecture_1"]);
printGauge("Infra and Network","1","automation",$data_array["maturity_architecture_2"]);
printGauge("Technical Debt","2","wow",$data_array["maturity_architecture_3"]);
printGauge("Observability and Monitoring","5","env",$data_array["maturity_architecture_4"]);


?>

</tbody>
</table>



	<!-- End of Tab1 Div -->    
      </div>


     <div id="tabs-3">
<h4>Suggested Follow-up Workshops</h4>
  <table class="bordered">
    <thead>
    <tr>
        <th>Area</th>        
        <th>Development</th>
        <th>Operations</th>
    </tr>
    </thead>
	<tbody> 
<!-- Read all the workshops from the json file, deduplicate then add per area -->
<?php
$scores = array(
array ('area' => "automation",'title' => "Automation",'ops' => $data_array['maturity_automation_1'], 'dev' => $data_array['maturity_architecture_1'], 'total' => $data_array['maturity_architecture_1'] + $data_array['maturity_automation_1']), 
array ('area' => "wayOfWorking",'title' => "Way of Working", 'ops' => $data_array['maturity_automation_2'], 'dev' => $data_array['maturity_architecture_2'], 'total' => $data_array['maturity_architecture_2'] + $data_array['maturity_automation_2']), 
array ('area' => "architecture", 'title' => "Architecture", 'ops' => $data_array['maturity_automation_3'], 'dev' => $data_array['maturity_architecture_3'], 'total' => $data_array['maturity_architecture_3'] + $data_array['maturity_automation_3']), 
array ('area' => "visionLeadership",'title' => "Vision and Leadership", 'ops' => $data_array['maturity_automation_4'], 'dev' => $data_array['maturity_architecture_4'], 'total' => $data_array['maturity_architecture_4'] + $data_array['maturity_automation_4']), 
);

$total = array_column($scores, 'total');
$titles = array_column($scores, 'title');
$areasForFeatures = array_column($scores, 'area');
$dev = array_column($scores, 'dev');
$ops = array_column($scores, 'ops');

$allWorkshops = $devWorkshops = $opsWorkshops = array();
$string = file_get_contents("questionsV2.json");
$json = json_decode($string, true);

for($i = 0; $i < 5; $i++) {

print "<tr><td>" . $titles[$i] . "</td>";
foreach ($json[$areasForFeatures[$i]]['workshops'] as $w) {

$cellLine = "";
$nospan="";
if ($areasForFeatures[$i] == "visionLeadership" || $areasForFeatures[$i] == "environment" || $areasForFeatures[$i] == "wayOfWorking") {
print "<td colspan=2  style='text-align:center'><b>Relevant for both Dev and Ops</b><br>";
$nospan="Y";
} else {
print "<td>";
}
	foreach ($w as $ws)
	{
		if(!in_array($ws, $allWorkshops)){
   	     array_push($allWorkshops, $ws);
			  $cellLine .=  $ws  . "<br>";
		}
   }
print $cellLine . "</td>";
		if ($nospan == "Y") {
		break;		
		}

	}
print "</tr>";
}
?>
</tbody>
</table>

      
      </div>

      <div id="tabs-4">

<?php

function putAreaComments($title, $area) {
$commentLabel = "comments_" . $area;
print "Looking for $commentLabel"; 
if ($data_array_info["$commentLabel"] != NULL) {
print "<h4>$title</h4>";
print '<p>' . $data_array_info["$commentLabel"] . "</p>";
}
}

if ($data_array_info['comments'] != "") {
print "<h4>General Comments</h4>";
print "<p>" . $data_array_info['comments'] . "</p>";
}

## Fudge here as can't seem to get it in a loop ... dodgy code alert!
if ($data_array_info['comments_automation'] != "") {
print "<h4>Automation</h4>";
print "<p>" . $data_array_info['comments_automation'] . "</p>";
}

if ($data_array['comments_wayOfWorking'] != "") {
print "<h4>Way of Working</h4>";
print "<p>" . $data_array['comments_wayOfWorking'] . "</p>";
}

if ($data_array['comments_architecture'] != "") {
print "<h4>Architecture</h4>";
print "<p>" . $data_array['comments_architecture'] . "</p>";
}

if ($data_array['comments_environment'] != "") {
print "<h4>Environment</h4>";
print "<p>" . $data_array['comments_environment'] . "</p>";
}

if ($data_array['comments_visionLeadership'] != "") {
print "<h4>Vision and Leadership</h4>";
print "<p>" . $data_array['comments_visionLeadership'] . "</p>";
}
?>      
	<!-- End of Tab4 Div -->    
      </div>

<!-- Start of comparisons -->
      <div id="tabs-5">

 <?php
if ($share == "on") {
print '<br>
<div id="average-dialog-dev" title="Average (Dev)">
<canvas id="myChartDev" width="400" height="200"></canvas>
</div>
<button id="average-opener-dev" class="ui-button ui-widget ui-corner-all">Average (Dev)</button>

<div id="average-dialog-ops" title="Average (Ops)">
<canvas id="myChartOps" width="400" height="200"></canvas>
</div>
<button id="average-opener-ops" class="ui-button ui-widget ui-corner-all">Average (Ops)</button>
<br><br>
<div id="average-dialog-dev-lob" title="Average for ' . $lob . ' (Dev)">
<canvas id="myChartDevLob" width="400" height="200"></canvas>
</div>
<button id="average-opener-dev-lob" class="ui-button ui-widget ui-corner-all">Average (Dev) for ' . $lob . '</button>

<div id="average-dialog-ops-lob" title="Average for ' . $lob . ' (Ops)">
<canvas id="myChartDevOps" width="400" height="200"></canvas>
</div>
<button id="average-opener-ops-lob" class="ui-button ui-widget ui-corner-all">Average (Ops) for ' . $lob . '</button>
<br><br>
';
} else {
print "<h4>Comparisons not available for this customer</h4>";
}
?>     
	<!-- End of comparisons -->    
      </div>


</div>

</div>
<!-- end of main content div -->
<!-- end of wrapper div -->


</div>


<script id="jsbin-javascript">
$(document).ready(function(){
  
  var mc = {
    '0-25'     : 'red',
    '26-50'    : 'orange',
    '51-100'   : 'green'
  };
  
function between(x, min, max) {
  return x >= min && x <= max;
}
  

  
  var dc;
  var first; 
  var second;
  var th;
  
  $('p').each(function(index){
    
    th = $(this);
    
    dc = parseInt($(this).attr('data-color'),10);
    
    
      $.each(mc, function(name, value){
        
        
        first = parseInt(name.split('-')[0],10);
        second = parseInt(name.split('-')[1],10);

        
        if( between(dc, first, second) ){
          th.addClass(value);
        }
      });
    
  });
});
</script>
<script>
  var g = new JustGage({
    id: "automation",
    value: <?php print $data_array['automation_total'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true,
        levelColorsGradient: false
    
  });
</script>
<script>
  var g = new JustGage({
    id: "wow",
    value: <?php print $data_array['wow_total'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true,
        levelColorsGradient: false        
  });
</script>


<script>
  var g = new JustGage({
    id: "arch",
    value: <?php print $data_array['architecture_total'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true,
        levelColorsGradient: false        
  });
</script>

<script>
  var g = new JustGage({
    id: "vision",
    value: <?php print $data_array['vision_total'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true,
        levelColorsGradient: false        
  });
</script>
<script>
  var g = new JustGage({
    id: "env",
    value: <?php print $data_array['environment_total'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true    
  });
</script>
<script>
  var g = new JustGage({
    id: "maturity_architecture_1",
    value: <?php print $data_array['maturity_architecture_1'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true
  });
</script>
<script>
  var g = new JustGage({
    id: "maturity_architecture_2",
    value: <?php print $data_array['maturity_architecture_2'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true
  });
</script>
<script>
  var g = new JustGage({
    id: "maturity_architecture_3",
    value: <?php print $data_array['maturity_architecture_3'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true
  });
</script>
<script>
  var g = new JustGage({
    id: "maturity_architecture_4",
    value: <?php print $data_array['maturity_architecture_4'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true
  });
</script>
<script>
  var g = new JustGage({
    id: "maturity_automation_1",
    value: <?php print $data_array['maturity_automation_1'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true
  });
</script>
<script>
  var g = new JustGage({
    id: "maturity_automation_2",
    value: <?php print $data_array['maturity_automation_2'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true
  });
</script>
<script>
  var g = new JustGage({
    id: "maturity_automation_3",
    value: <?php print $data_array['maturity_automation_3'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true
  });
</script>
<script>
  var g = new JustGage({
    id: "maturity_automation_4",
    value: <?php print $data_array['maturity_automation_4'] . "\n"; ?>,
    min: 0,
    max: 5,
    decimals: 2,
        humanFriendly : true,
        gaugeWidthScale: 1.0,
        levelColors: ["#FFDA6B","#FFDA6B", "#ffa500", "#33C7FF", "#90ee90", "00ff00"],
        counter: true
  });
</script>
</body>
</html>
