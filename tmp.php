<?php
session_start();
include_once 'dbconnect.php';
#phpinfo();
connectDB();
parse_str($_SERVER["QUERY_STRING"], $data);
$data['client'] = $data['customerName']; // FIXME hack

$chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
$data['hash'] = substr(str_shuffle($chars), 0, 5);

## Get the username from the userId
#$q1 = "select name from users where id = '" . $data['userId'] . "'";
$q1 = "select name from users where id = '" . $_SESSION['usr_id'] . "'";
$res = mysqli_query($db, $q1);
$row = mysqli_fetch_assoc($res);
$data['user'] = $row['name'];

##Prepopulate some of the vars related to toggle fields in case they are not set to yes later
$need_architecture_1="";
$need_architecture_2="";
$need_architecture_3="";
$need_architecture_4="";
$need_automation_1="";
$need_automation_2="";
$need_automation_3="";
$need_automation_4="";
$need_environment_1="";
$need_environment_2="";
$need_environment_3="";
$need_environment_4="";
$need_wow_1="";
$need_wow_2="";
$need_wow_3="";
$need_wow_4="";
$goal_architecture_1="";
$goal_architecture_2="";
$goal_architecture_3="";
$goal_architecture_4="";
$goal_automation_1="";
$goal_automation_2="";
$goal_automation_3="";
$goal_automation_4="";
$goal_environment_1="";
$goal_environment_2="";
$goal_environment_3="";
$goal_environment_4="";
$goal_wow_1="";
$goal_wow_2="";
$goal_wow_3="";
$goal_wow_4="";


##Create fields array for Database columns

$fields = array('user','client','rhEmail','country','lob','hash','share','demo','project','need_architecture_1','need_architecture_2','need_architecture_3','need_architecture_4','need_automation_1','need_automation_2','need_automation_3','need_automation_4','need_wow_1','need_wow_2','need_wow_3','need_wow_4','need_environment_1','need_environment_2','need_environment_3','need_environment_4','goal_architecture_1','goal_architecture_2','goal_architecture_3','goal_architecture_4','goal_automation_1','goal_automation_2','goal_automation_3','goal_automation_4','goal_wow_1','goal_wow_2','goal_wow_3','goal_wow_4','goal_environment_1','goal_environment_2','goal_environment_3','goal_environment_4');
$fields_maturity = array('hash','architecture_total','maturity_architecture_1','maturity_architecture_2','maturity_architecture_3','maturity_architecture_4','automation_total','maturity_automation_1','maturity_automation_2','maturity_automation_3','maturity_automation_4','environment_total','maturity_environment_1','maturity_environment_2','maturity_environment_3','maturity_environment_4','wow_total','maturity_wow_1','maturity_wow_2','maturity_wow_3','maturity_wow_4');

#Assign fields from the Form in the data array to actual fields
foreach ($fields as $field) {
	$$field = mysqli_real_escape_string($db, $data[$field]);
}

if (!isset ($share)) {
	$share = "off";
}

if (!isset ($demo)) {
	$demo = "Yes";
} 

#original query 
#$qq = "INSERT INTO data (" . implode(',', $fields).") VALUES ('$user','$client','$rhEmail','$country','$lob','$need_architecture_1','$need_architecture_2','$need_automation_1','$need_automation_2','$need_environment_1','$need_environment_2','$need_wow_1','$need_wow_2',$o1,$o2,$o3,$o4,$o5,$d1,$d2,$d3,$d4,$d5,'$hash','$share','$demo','$project','$comments','$comments_automation','$comments_wayOfWorking','$comments_architecture','$comments_environment','$comments_visionLeadership')";

#new data model queries
$qq = "INSERT INTO data (" . implode(',', $fields).") VALUES ('$user','$client','$rhEmail','$country','$lob','$hash','$share','$demo','$project','$need_architecture_1','$need_architecture_2','$need_architecture_3','$need_architecture_4','$need_automation_1','$need_automation_2','$need_automation_3','$need_automation_4','$need_wow_1','$need_wow_2','$need_wow_3','$need_wow_4','$need_environment_1','$need_environment_2','$need_environment_3','$need_environment_4','$goal_architecture_1','$goal_architecture_2','$goal_architecture_3','$goal_architecture_4','$goal_automation_1','$goal_automation_2','$goal_automation_3','$goal_automation_4','$goal_wow_1','$goal_wow_2','$goal_wow_3','$goal_wow_4','$goal_environment_1','$goal_environment_2','$goal_environment_3','$goal_environment_4')";
#print $qq;
$result = mysqli_query($db, $qq);


foreach ($fields_maturity as $field) {
        $$field = mysqli_real_escape_string($db, $data[$field]);
}


$maturity_architecture = ($maturity_architecture_1 + $maturity_architecture_2 + $maturity_architecture_3 + $maturity_architecture_4 ) / 4;
$maturity_automation = ($maturity_automation_1 + $maturity_automation_2 + $maturity_automation_3 + $maturity_automation_4 ) /4;
$maturity_environment = ($maturity_environment_1 + $maturity_environment_2 + $maturity_environment_3 + $maturity_environment_4 ) /4;
$maturity_wow = ($maturity_wow_1 + $maturity_wow_2 + $maturity_wow_3 + $maturity_wow_4 ) /4;

$qq2 = "INSERT INTO maturity_scoring (" . implode(',', $fields_maturity).") VALUES ('$hash',$maturity_architecture,$maturity_architecture_1,$maturity_architecture_2,$maturity_architecture_3,$maturity_architecture_4,$maturity_automation,$maturity_automation_1,$maturity_automation_2,$maturity_automation_3,$maturity_automation_4,$maturity_environment,$maturity_environment_1,$maturity_environment_2,$maturity_environment_3,$maturity_environment_4,$maturity_wow,$maturity_wow_1,$maturity_wow_2,$maturity_wow_3,$maturity_wow_4)";

$result2 = mysqli_query($db, $qq2);
// TODO check $result


//Calculating the client priorities and inserting in client_scoring table
$areas = array('architecture','automation','environment','wow');
$architecture_subareas = array('1','2','3','4');
$automation_subareas = array('','','','');
$environment_subareas = array('','','','');
$wow_subareas = array('','','','');
$count_areas = sizeof($areas);
$count_architecture = sizeof($architecture_subareas);
$count_automation = sizeof($automation_subareas);
$count_environment = sizeof($environment_subareas);
$count_wow = sizeof($wow_subareas);
$i=0;
$score=0;
    $qq_cs = "INSERT INTO client_scoring (hash) VALUES ('$hash')";
    $result_cs = mysqli_query($db, $qq_cs);
    while ($i < $count_areas) {
    $area=$areas[$i];
    $subar = ${$area."_subareas"};
    $count_subareas = sizeof($subar);
    $count_sub = ${"count_$area"};
    $ii=0;
    $area_score=0;
    while ($ii < $count_sub) {
    $area_average=0;
    $iii = $ii+1;
    #$con1 = "need_".$area."_".$iii;
    #$con2 = "goal_".$area."_".$iii;
    $con1 = ${"need_".$area."_".$iii};
    $con2 = ${"goal_".$area."_".$iii};
    if ($con1 == "on" || $con2 == "on") {
    $client_pov=1;
    $score=2;
     }
    else if ($con1 == "on" || $con2 == ""){
    $client_pov=3;
    $score=1;
     }
    else if ($con1 == "" || $con2 == "on"){
    $client_pov=2;
    $score=1.5;
     }
    else if ($con1 == "" || $con2 == ""){
    $client_pov=4;
    $score=0;
     } 
    else { }
    $ii++;
    $area_score= $area_score + $score;
    $col = $area."_area_".$iii;
    $qq = "UPDATE client_scoring SET $col=$score WHERE hash = '$hash'";
    $result3 = mysqli_query($db, $qq);
    }
    $i++;
    $area_avg= $area_score/$count_sub;
    $col = $area."_total";
    $qq = "UPDATE client_scoring SET $col=$area_avg WHERE hash = '$hash'";
    $result4 = mysqli_query($db, $qq);
    };




//Calculating interpolated results for client and maturity assessment
// TODO
header("Location: results.php?hash=$hash");
?>
