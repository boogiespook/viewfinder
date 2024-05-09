

<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>Viewfinder Detailed Report</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">

      <link rel="stylesheet" href="css/table.css">

      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>

   </head>
   <!-- body -->
   <body class="main-layout">

<!--
Images:
<a href="https://www.freepik.com/search">Icon by Freepik</a>



-->

<?php

parse_str($_SERVER["QUERY_STRING"], $data);

$string = file_get_contents("../controls.json");
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

      <!-- header -->
      <header>
         <!-- header inner -->
         <div  class="head_top">
            <div class="header">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                           <div class="center-desk">
                              <div class="logo">
                                 <a href="index.php"><img src="images/telescope-viewfinder.png" alt="#" /></a>
                              </div>
                           </div>
                        </div>
                     </div>
                     
                  </div>
               </div>
            </div>
            <!-- end header inner -->
            <!-- end header -->
            <!-- banner -->
            <section class="banner_main">
               <div class="container-fluid">
                  <div class="row d_flex">
                     <div class="col-md-4">
                        <div class="text-bg">
                           <h1>Security Maturity Assessment</h1>
<!--                           <p>Viewfinder is an open source community creating a capability which enables holistic and comprehensive evaluations of your organisation's security posture.  It benchmarks your current security practices against either a set of extensible, opinionated criteria, as well as industry standards and best practices, providing you with actionable insights to enhance your security posture.</p> -->
                           <h3> How does this radar chart help me?</h3>
                           <ul>
                              <li><b>Identify Weaknesses</b> Pinpoint vulnerabilities and weaknesses in your security infrastructure.</li>
                              <li><b>Help with Risk Mitigation</b> Understand potential risks and how to mitigate them effectively.</li>
                              <li><b>Resource Optimisation</b> Allocate resources more effectively by focusing on areas that need improvement the most.</li>
                           </ul>
                        </div>
                     </div>
                     <div class="col-md-5">
                        <div class="text-img">
                           
                           <figure>
                           <div class="radarChart"></div>
                           </figure>
                        </div>
                     </div>

                  </div>
               </div>
            </section>
         </div>
      </header>
      <div class="pagebreak"> </div>
      <!-- end banner -->
      <!-- business -->
      <div class="business">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <span>Maturity Levels</span>
                     <h3>As of <?php print date('l jS \of F Y'); ?> </h3>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="titlepage">
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
   <img src="images/Security-Maturity-Assessment.png" alt="#"/>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end business -->
      <!-- Projects -->
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepageLeft">

                  <!-- Start of recomendations -->

                  <?php
foreach ($controls as $control) {
    $highest=0;	
    $qnum = $json[$control]['qnum'];
	$score = $controlTotal[$qnum];
	$title = $json[$control]['title'];
	array_push($nextDomain, $title);
   print '<div class="pagebreak"> </div>';
    print "<br><h2>$title - <span class='cellHeader" . getRating($score) . "'>". getRating($score) . " Level</span></h2><div>";

    
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
        print "<br><p class=why-what>Definition of " . $json[$control][$nextLevel] . " </p><p>" . $json[$control][$nextSummary] . "</p>";

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
			print "<p class=why-what>Level $notthere </p>";
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
   $randomImage = rand(1, 9);
   print "<img class=smallImage src=images/tech-image-" . $randomImage . ".png>";
    print "</div>";

}
?>

                  <!-- End of recommendations -->





                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-10 offset-md-1">
                  <div class="row">
 
                  </div>
               </div>
            </div>
         </div>
      <!-- end projects -->
      <!-- Testimonial -->
      <div class="section">
         <div class="container">
            <div id="" class="Testimonial">
               <div class="row">
                  <div class="col-md-12">
                     <div class="titlepage">
                        <h2>Need more information ?</h2>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-3">
                     <div class="Testimonial_box">
                     </div>
                  </div>
                  <div class="col-md-9">
                     <div class="Testimonial_box">
                        <p>Don't wait until it's too late. Take proactive steps and empower yourselves with Project Telescope, and enable proactive security within your customer’s organisation with Project Telescope: Viewfinder Security Maturity Assessments. Contact your Red Hat account team for more information and take the first step towards a more secure future. 
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
     
      <!-- end Testimonial -->
      
      <!--  footer -->
      <footer>
         <div class="footer">
            <div class="container">
               <div class="row">

               </div>
            </div>
            <div class="copyright">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12">
                        <p>Copyright 2024 All Right Reserved </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- end footer -->
      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
      <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>

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
   </body>
</html>

