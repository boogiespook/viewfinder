<!doctype html>
<html lang="en-us" class="pf-theme-dark">
  <head>
  <title>Viewfinder - Results</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/table.css">

<link rel="stylesheet" href="css/patternfly.css" />
<link rel="stylesheet" href="css/patternfly-addons.css" />
<link rel="stylesheet" href="css/tab.css">
<link rel="stylesheet" href="css/table2.css">


<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
<script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>
<script>
  $( function() {
    $( "#accordion" ).accordion({
      heightStyle: "content",
      collapsible: true,
      active : 'none'
    });
  } );
  </script>

</head>
<body>
  <header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <a class="pf-c-page__header-brand-link" href="index.php">
                  <img class="pf-c-brand" src="images/telescope-viewfinder.png" alt="Viewfinder logo" />
                  </a>
                </div>
</header>
<?php
$urlData = "./report/index.php?" . $_SERVER["QUERY_STRING"];
parse_str($_SERVER["QUERY_STRING"], $data);

$backendFile = "controls-" . $data['profile'] . '.json';
$string = file_get_contents($backendFile);
$json = json_decode($string, true);
$nextSteps = array();
$nextStepsHow = array();
$nextDomain = array();
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
	if($score == "0"){
		$rating = "Not Rated";
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

?>


<div class="container">

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Radar')" id="defaultOpen">Radar Chart & Maturity Levels</button>
  <button class="tablinks" onclick="openTab(event, 'Recommendations')">Recommendations</button>
  <button class="tablinks" onclick="openTab(event, 'TableOutput')">Maturity Table</button> 
  <?php
  if (isset($_REQUEST['framework'])) {
	print '<button class="tablinks" onclick="openTab(event, \'Frameworks\')">Security Frameworks</button>';
}
  ?>
  <button class="tablinks""><a href="<?php print $urlData; ?>" target= _blank>Detailed Output</a>&nbsp; <i class='fas fa-external-link-alt'></i></button> 

</div>

<div id="Radar" class="tabcontent">

<div class="htmlChart">
<div class="radarChart"></div>
</div>

<div class="bigtableLeft">
<h1 class="profileHeader">Profile: <?php print $data['profile'];?> </h1>

<table class="spacedTable">
	<thead>
		<tr>
			<th>Control</th>
			<th>Rating</th>
			</tr>
		</tr>
</thead>


<?php
$totalScore = 0;
## Work out all the stuff for the table
foreach ($controls as $control) {
	print "<tr>";
	$title = $json[$control]['title'];
	$qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$totalScore += $score;
	#print "<td><i class='fa-regular fa-" . $qnum . "'>&nbsp; &nbsp; </i>" . $title . "</td>";
	print "<td>" . $title . "</td>";
	print "<td class='cell" . getRating($score) . "'>" . getRating($score) . " ($score out of 36)</td>";
	print "</tr>";
}
print '</table>';
print "<br><table><td class='cell" . getTotalRating($totalScore) ."'>Overall rating: " . getTotalRating($totalScore) . " ($totalScore out of 252)</td></tr></table>";

?>
</div>
</div>
<!-- Detailed Output -->
<div id="Recommendations" class="tabcontent">
<div id="accordion">
<?php
foreach ($controls as $control) {
    $highest=0;	
    $qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$title = $json[$control]['title'];
	array_push($nextDomain, $title);
    print "<h3>$title <span class='cellHeader" . getRating($score) . "'>". getRating($score) . "</span></h3><div>";

    
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
        $nextSummary = $nextLevel . '-summary';
        print "<h4 class=title-text>Recommendation</h4>"; 
        print "<p>Start to work on preparing for actions concerning " . $json[$control][$nextLevel] . " (Level $nextLevel)<p>";
        print "<br><p class=why-what>What is " . $json[$control][$nextLevel] . " ?</p><p>" . $json[$control][$nextSummary] . "</p>";

        if ($json[$control][$nextRecommendation] != "") {
            print "<br><p class=why-what>How</p>";
            print "<p>" . $json[$control][$nextRecommendation] . "<p>";
			array_push($nextSteps,$json[$control][$nextLevel]);
			array_push($nextStepsHow,$json[$control][$nextSummary]);
        }# else {
        #    print "<p>Start to work on preparing for actions concerning " . $json[$control][$nextLevel] . " (Level $nextLevel)<p>";
        #    print "<p>" . $json[$control][$nextSummary] . "</p>";
        #}
        print "<br><h4 class=why-what>Conversations to have</h4>";
        $nextConversations = $nextLevel . '-conversation';
        if ($json[$control][$nextConversations] != "") {
            print $json[$control][$nextConversations];
        } else {
            print "No conversations currently for " . $json[$control][$nextLevel];
        }
    }else {
        print "<p>You're doing great as you are!</p>";
    }


## Check for any gaps
if ($levelArray) {
	#print "Max: " . max($levelArray) . "<br>";
	$allLevels = range(1,max($levelArray));
	$missing = array_diff($allLevels,$levelArray);
	if ($missing) {
		print "<br><br><h4 class=why-what>Skipped Level(s)</h4>";
		foreach ($missing as $notthere) {
			$skippedRecommendation = $notthere . '-recommendation';
			print "Level $notthere - ";
			if ($json[$control][$skippedRecommendation] != "") {
			print $json[$control][$skippedRecommendation] . ". ";
			} else {
                $notthereComment = $notthere . "-summary";
#				print_r($json[$control][$notthere]);
                print $json[$control][$notthereComment];
			}
			print "<br>";
		}
	}
	}
    
    print "</div>";

}
?>

</div>
<!-- End of Detailed Output -->

</div>

<!-- Start of table output  -->

<div id="TableOutput" class="tabcontent">

<?php
  function getStatus ($capabilityId) {
    if ($capabilityId == "1") {
      $status = "completed";
    } else {
      $status = "notcompleted";
    }
    print $status;
  }

  $controlDetail = array_fill(1,8,0);
  $controlDetails = array_fill(1,8,$controlDetail);
  
  foreach($data as $field=>$value){
	  if (strpos($field,"control") !== false){
	  $controlNumber = substr($field,7,1);
	  $controlDetails[$controlNumber][$value] = 1;
  }
  }   
?>

<div class="bigtable">

<table><thead><tr>
<th class="table-header">Rating</th>
<th class="table-header">Secure Infrastructure</th>
<th class="table-header">Secure Data</th>
<th class="table-header">Secure Identity</th>
<th class="table-header">Secure Application</th>
<th class="table-header">Secure Network</th>
<th class="table-header">Secure Recovery</th>
<th class="table-header">Secure Operations</th>

</tr></thead>
<tr>
<td class="advanced"></td>
<td class="<?php getStatus($controlDetails[1][8]); ?>">Identity-Based Perimeter</td>                 
<td class="<?php getStatus($controlDetails[2][8]); ?>">Anomaly Detection </td>                        
<td class="<?php getStatus($controlDetails[3][8]); ?>">Contextual / Risk Based Access </td>           
<td class="<?php getStatus($controlDetails[4][8]); ?>">Interactive Application Security Testing </td> 
<td class="<?php getStatus($controlDetails[5][8]); ?>">Zero Trust Network Access </td>                
<td class="<?php getStatus($controlDetails[6][8]); ?>">Predictive Recovery </td>                      
<td class="<?php getStatus($controlDetails[7][8]); ?>">Purple Teaming </td>                           
</tr>

<tr>
<td class="advanced">Advanced</td>
   
<td class="<?php getStatus($controlDetails[1][7]); ?>">Service Mesh Security</td>               
<td class="<?php getStatus($controlDetails[2][7]); ?>">Immutable Storage</td>                   
<td class="<?php getStatus($controlDetails[3][7]); ?>">AI/ML Anomaly Detection</td>             
<td class="<?php getStatus($controlDetails[4][7]); ?>">Runtime Application Self Protection</td> 
<td class="<?php getStatus($controlDetails[5][7]); ?>">Microsegmentation</td>                   
<td class="<?php getStatus($controlDetails[6][7]); ?>">Advanced Key Management</td>             
<td class="<?php getStatus($controlDetails[7][7]); ?>">APT Detection & Response</td>            
</tr>

<tr>
<td class="advanced"></td>
<td class="<?php getStatus($controlDetails[1][6]); ?>">Container Runtime Security</td>      
<td class="<?php getStatus($controlDetails[2][6]); ?>">Automated Posture Management</td>    
<td class="<?php getStatus($controlDetails[3][6]); ?>">Identity Federation</td>             
<td class="<?php getStatus($controlDetails[4][6]); ?>">Container Scanning</td>              
<td class="<?php getStatus($controlDetails[5][6]); ?>">Secure Connections</td>              
<td class="<?php getStatus($controlDetails[6][6]); ?>">Storage Scanning & Monitoring</td>   
<td class="<?php getStatus($controlDetails[7][6]); ?>">Threat Intelligence Integration</td> 

</tr>

<tr>
<td class="strategic"></td>
<td class="<?php getStatus($controlDetails[1][5]); ?>">Secrets Management</td>                  
<td class="<?php getStatus($controlDetails[2][5]); ?>">Loss Prevention</td>                     
<td class="<?php getStatus($controlDetails[3][5]); ?>">Privileged Access Managemet</td>         
<td class="<?php getStatus($controlDetails[4][5]); ?>">Web Application Firewall</td>            
<td class="<?php getStatus($controlDetails[5][5]); ?>">Traffic Analysis</td>                    
<td class="<?php getStatus($controlDetails[6][5]); ?>">Lifecycle Management</td>                
<td class="<?php getStatus($controlDetails[7][5]); ?>">Orchestration, Automation, Response</td> 
</tr>

<tr>
<td class="strategic">Strategic</td>
<td class="<?php getStatus($controlDetails[1][4]); ?>">Automated Policy / Enforcement</td>       
<td class="<?php getStatus($controlDetails[2][4]); ?>">Tokenization</td>                         
<td class="<?php getStatus($controlDetails[3][4]); ?>">Single Sign On</td>                       
<td class="<?php getStatus($controlDetails[4][4]); ?>">Dynamic Application Security Testing</td> 
<td class="<?php getStatus($controlDetails[5][4]); ?>">Intrusion Detection / Prevention</td>     
<td class="<?php getStatus($controlDetails[6][4]); ?>">Automated Failovers</td>                  
<td class="<?php getStatus($controlDetails[7][4]); ?>">Endpoint Detection & Response</td>        
</tr>

<tr>
<td class="strategic"></td>
<td class="<?php getStatus($controlDetails[1][3]); ?>">Logging & Monitoring</td>                    
<td class="<?php getStatus($controlDetails[2][3]); ?>">Access Control</td>                          
<td class="<?php getStatus($controlDetails[3][3]); ?>">Multi-Factor Identification</td>             
<td class="<?php getStatus($controlDetails[4][3]); ?>">Secure Code Practices</td>                   
<td class="<?php getStatus($controlDetails[5][3]); ?>">Access Control Lists</td>                    
<td class="<?php getStatus($controlDetails[6][3]); ?>">Consistent Versioning</td>                   
<td class="<?php getStatus($controlDetails[7][3]); ?>">Security Information & Event Management</td> 
</tr>

<tr>
<td class="foundation"></td>
<td class="<?php getStatus($controlDetails[1][2]); ?>">Segmentation / Isolation</td>            
<td class="<?php getStatus($controlDetails[2][2]); ?>">Encryption</td>                          
<td class="<?php getStatus($controlDetails[3][2]); ?>">Role-Based Access Control</td>           
<td class="<?php getStatus($controlDetails[4][2]); ?>">Static Application Security Testing</td> 
<td class="<?php getStatus($controlDetails[5][2]); ?>">Secure Protocols</td>                    
<td class="<?php getStatus($controlDetails[6][2]); ?>">Disaster Recovery Plan</td>              
<td class="<?php getStatus($controlDetails[7][2]); ?>">Anti-Virus scan</td>                     
</tr>

<tr>
<td class="foundation">Foundation</td>
<td class="<?php getStatus($controlDetails[1][1]); ?>">Config Management</td>        
<td class="<?php getStatus($controlDetails[2][1]); ?>">Classification</td>           
<td class="<?php getStatus($controlDetails[3][1]); ?>">Passwords</td>                
<td class="<?php getStatus($controlDetails[4][1]); ?>">Dependency Management</td>    
<td class="<?php getStatus($controlDetails[5][1]); ?>">Firewalls & Segmentation</td> 
<td class="<?php getStatus($controlDetails[6][1]); ?>">Backup & Redundancy</td>      
<td class="<?php getStatus($controlDetails[7][1]); ?>">Incident Response Plan</td>   
</tr>

</table>

</div>


</div>
<!-- End of table output  -->




<!-- Start of Security Frameworks -->
<div id="Frameworks" class="tabcontent">


<?php
if (isset($_REQUEST['framework'])) {
	$stringFrameworks = file_get_contents("compliance.json");
	$jsonFrameworks = json_decode($stringFrameworks, true);

$frameworkCount = count($_REQUEST['framework']);
for ($i = 0; $i < $frameworkCount; $i++) {
	foreach ($jsonFrameworks as $framework) {
		if ($framework['name'] == $_REQUEST['framework'][$i]) {
        $linkFile = $framework['link'];
		print "<br><div class='niceList'>";
	print "<ul>";

	if (file_exists($linkFile)) {
	include $linkFile; 
	} else {
		print "<h3 class='frameworkHeader'>No current information for " . $framework['name'] . "<br>";
	}
  print "</ul></div>";

	}
}
}
}
?>
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
						<?php
						$numControls = 1;
						foreach ($controls as $control) {
							$title = $json[$control]['title'];
							print '{axis:"' . $title . '",value: ' . $controlTotal[$numControls]. '},';		
							$numControls++;
						}
						?>

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
function openTab(evt, cityName) {
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
<script type="text/javascript" >
document.getElementById("defaultOpen").click();
</script>
</body>
  </html>