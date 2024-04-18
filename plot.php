<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Viewfinder - Results</title>
<link rel="stylesheet" type="text/css" href="css/overpass.css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/table.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/tab.css" />

<link rel="stylesheet" href="css/patternfly.css" />
<link rel="stylesheet" href="css/patternfly-addons.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>

</head>

<body>
<header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <div class="pf-c-page__header-brand-toggle">
                  </div>
                  <a class="pf-c-page__header-brand-link" href="index.php">
                  <img class="pf-c-brand" src="images/telescope-viewfinder.png" alt="Viewfinder logo" />
                  </a>
                </div>
</header>
<div class="bigtableLeft">
<h1 class="headers">Breakdown By Control</h1>
<table>
	<thead>
		<tr>
			<th>Control</th>
			<th>Rating</th>
			</tr>
		</tr>
</thead>
<?php

parse_str($_SERVER["QUERY_STRING"], $data);
#print_r($data);
$string = file_get_contents("controls.json");
$json = json_decode($string, true);
$controls = array();
foreach($json as $key => $value) {
	array_push($controls,$key);
	}
	
$controlTotal = array_fill(0,8,0);
$controlDetails = array(array_fill(0,8,0));

foreach($data as $field=>$value){
	if (strpos($field,"control") !== false){
    $controlNumber = substr($field,7,1);
	$controlTotal[$controlNumber] += $value;
}
}

function getRating($score) {
	$rating  = "Foundation";
	switch($score) {
		case ($score > 9 && $score < 22):
			$rating = "Strategic";
			break;
		case ($score > 27):
			$rating = "Advanced";
	}
	return $rating;
}

function getTotalRating($score) {
	$rating  = "Foundation";
	switch($score) {
		case ($score > 84 && $score < 168):
			$rating = "Strategic";
			break;
		case ($score > 169):
			$rating = "Advanced";
	}
	return $rating;
}

$totalScore = 0;
## Work out all the stuff for the table
foreach ($controls as $control) {
	print "<tr>";
	$title = $json[$control]['title'];
	$qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$totalScore += $score;
	print "<td>" . $title . "</td>";
	print "<td class='cell" . getRating($score) . "'>" . getRating($score) . " ($score out of 36)</td>";
	print "</tr>";
}
print '</table>';

print "<br><table><td class='cell" . getTotalRating($totalScore) ."'>Overall rating: " . getTotalRating($totalScore) . " ($totalScore out of 252)</td></tr></table>";
?>

</div>
<div class="bigtableRight">
<h1 class="headers">Next Steps</h1>
<div class="tab">
<?php
foreach ($controls as $control) {
	$title = $json[$control]['title'];
	print '<button class="tablinks" onclick="putTab(event, \'' . $control . '\')">' . $title .'</button>';
}
?>

</div>
<?php
foreach ($controls as $control) {
$highest=0;	
print '<div id="' . $control . '" class="tabcontent">';
## Write code to get correct results
$qnum = $json[$control]['qnum'];
$levelArray = array();
## Get the highest score per capability & keep the results
foreach ($data as $key => $value) {
if (preg_match("/^control$qnum-[0-9]*/", $key)) {
	array_push($levelArray, substr($key, -1));
	$highest++;
	  }
}

$nextLevel = $highest + 1;
if ($nextLevel < 9) {
	## Check if there is a recommendation for the next level
	$nextRecommendation = $nextLevel . '-recommendation'; 
	if ($json[$control][$nextRecommendation] != "") {
		print "The recommendation for reaching the next level is to:<br> " . $json[$control][$nextRecommendation] . "<br>";

	} else {
		print "No current recommendations available for reaching level concerning '" . $json[$control][$nextLevel] . "'<br>";
	}
}else {
	print "You're doing great as you are!";
}

## Check for any gaps
if ($levelArray) {
	#print "Max: " . max($levelArray) . "<br>";
	$allLevels = range(1,max($levelArray));
	$missing = array_diff($allLevels,$levelArray);
	if ($missing) {
		print "<ul>";
		print "You have skipped something:<br>";
		foreach ($missing as $notthere) {
			$skippedRecommendation = $notthere . '-recommendation';
			print "Level $notthere: ";
			if ($json[$control][$skippedRecommendation] != "") {
			print $json[$control][$skippedRecommendation];
			} else {
				print "No current recommendations";
			}
			print "<br>";
		}
		print "</ul>";
	}
	}

#print '<ul><li class="bullet">Control ' . $qnum . ': To reach the next level of maturity, consider implementing Infrastructure as Code to include config management and patching</li></ul>';
print "</div>";
}
?>


</div>
</div>
<div class="whiteBackground">
<div class="radarChart"></div>
</div>
		<script src="js/radarChart.js"></script>	
		<script>
      
      /* Radar chart design created by Nadieh Bremer - VisualCinnamon.com */
      
			////////////////////////////////////////////////////////////// 
			//////////////////////// Set-Up ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var margin = {top: 100, right: 100, bottom: 100, left: 100},
				width = Math.min(700, window.innerWidth - 10) - margin.left - margin.right,
				height = Math.min(width, window.innerHeight - margin.top - margin.bottom - 20);
					
			////////////////////////////////////////////////////////////// 
			////////////////////////// Data ////////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var data = [
					  [
						{axis:"Secure Infrastructure",value:<?php echo $controlTotal[1]; ?>},
						{axis:"Secure Data",value:<?php echo $controlTotal[2]; ?>},
						{axis:"Secure Identity",value:<?php echo $controlTotal[3]; ?>},
						{axis:"Secure Application",value:<?php echo $controlTotal[4]; ?>},
						{axis:"Secure Network",value:<?php echo $controlTotal[5]; ?>},
						{axis:"Secure Recovery",value:<?php echo $controlTotal[6]; ?>},
						{axis:"Secure Operations",value:<?php echo $controlTotal[7]; ?>}
					  ]
					];
			////////////////////////////////////////////////////////////// 
			//////////////////// Draw the Chart ////////////////////////// 
			////////////////////////////////////////////////////////////// 

			var color = d3.scale.ordinal()
				.range(["#CC333F","#CC333F","#00A0B0"]);
				
			var radarChartOptions = {
			  w: width,
			  h: height,
			  margin: margin,
			  maxValue: 0.5,
			  roundStrokes: true,
			  color: color,
			};
			//Call function to draw the Radar chart
			RadarChart(".radarChart", data, radarChartOptions);
		</script>
<script type="text/javascript" >
function putTab(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>
</body>
</html>