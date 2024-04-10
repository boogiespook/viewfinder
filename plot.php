<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Security Assessment</title>
<link rel="stylesheet" type="text/css" href="css/overpass.css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/table.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>

</head>

<body>
<?php
parse_str($_SERVER["QUERY_STRING"], $data);
#$chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
#$data['hash'] = substr(str_shuffle($chars), 0, 5);
#print_r($data);

$controlTotal = array_fill(0,8,0);
#$controlDetails = array_fill(0,8,0);
$controlDetails = array(array_fill(0,8,0));

foreach($data as $field=>$value){
	if (strpos($field,"control") !== false){
    $controlNumber = substr($field,7,1);
    $tt = 0;
	$controlTotal[$controlNumber] += $value;
}
}

## Work out all the stuff for the table

print_r($data);
?>

<div class="bigtable">

<table><thead><tr>
<th>Rating</th>
<th>Secure Infrastructure</th>
<th>Secure Data</th>
<th>Secure Identity</th>
<th>Secure Application</th>
<th>Secure Network</th>
<th>Secure Recovery</th>
<th>Secure Operations</th>

<?php
function getSelected($cell) {


}
?>

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
</div>
<div class="radarChart"></div>

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
			  color: color
			};
			//Call function to draw the Radar chart
			RadarChart(".radarChart", data, radarChartOptions);
		</script>

</body>
</html>