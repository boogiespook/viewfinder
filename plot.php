<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Security Assessment</title>
<link rel="stylesheet" type="text/css" href="css/overpass.css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/table.css">
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

<div class="bigtable">
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
		case ($score > 4 && $score < 11):
			$rating = "Strategic";
			break;
		case ($score > 12):
			$rating = "Advanced";
	}
	return $rating;
}
$totalScore = 0;
## Work out all the stuff for the table
$controls = array("SecureInfrastructure","SecureData","SecureIdentity","SecureApplication","SecureNetwork","SecureRecovery","SecureOperations");
#print_r($controlTotal);
foreach ($controls as $control) {
	print "<tr>";
	$title = $json[$control]['title'];
	$qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$totalScore += $score;
	print "<td>" . $title . "</td>";
	print "<td class='cell" . getRating($score) . "'>" . getRating($score) . "</td>";
	print "</tr>";

}
print "<br><p>Total Score: $totalScore </p><br>";
?>
</table>
</div>
<!-- <div class="bigtable">

<table><thead><tr>
<th>Rating</th>
<th>Secure Infrastructure</th>
<th>Secure Data</th>
<th>Secure Identity</th>
<th>Secure Application</th>
<th>Secure Network</th>
<th>Secure Recovery</th>
<th>Secure Operations</th>


</tr></thead>
<tr>
<td class="advanced"></td>
<td class="notcompleted">Identity-Based Perimeter</td>                 
<td class="notcompleted">Anomaly Detection</td>                        
<td class="notcompleted">Contextual / Risk Based Access</td>           
<td class="notcompleted">Interactive Application Security Testing</td> 
<td class="notcompleted">Zero Trust Network Access</td>                
<td class="notcompleted">Predictive Recovery</td>                      
<td class="notcompleted">Purple Teaming</td>                           
</tr>
</table>
</div> -->
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
			  //maxValue: 0.5,
			  levels: 7,
			  roundStrokes: true,
			  color: color,
			  
			};
			//Call function to draw the Radar chart
			RadarChart(".radarChart", data, radarChartOptions);
		</script>

</body>
</html>