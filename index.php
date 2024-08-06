<!doctype html>

<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Viewfinder Maturity Assessment</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/brands.css" />
      <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="css/tab.css" />
      <link rel="stylesheet" href="css/patternfly.css" />
      <link rel="stylesheet" href="css/patternfly-addons.css" />
      <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
      
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <script>
	//style all the dialogue
	$( function() {
		$(".dialog_help").dialog({
			modal: true,
			autoOpen: false,
			width: 500,
			height: 300,
			dialogClass: 'ui-dialog-osx'
		});
	});
	
	//opens the appropriate dialog
	$( function() {
		$(".opener").click(function () {
			//takes the ID of appropriate dialogue
			var id = $(this).data('id');
		   //open dialogue
			$(id).dialog("open");
		});
	});
</script>

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
                <div class="widget">
                  <?php
                  if (isset($_REQUEST['profile'])) {
                    $profile = $_REQUEST['profile'];
                  } else {
                    $profile = "Core";
                  }
                  ?>
              <a href="index.php?profile=Core"><button>Core</button></a>&nbsp
              <a href="index.php?profile=NIST"><button>NIST</button></a>&nbsp
            </div>
</header>
<div class="container">
<?php
$string = file_get_contents("controls-$profile.json");

$json = json_decode($string, true);
$controls = array();
foreach($json as $key => $value) {
	array_push($controls,$key);
	}

function getControls ($area,$json) {
$i=1;
$qnum = $json[$area]['qnum'];
$infoId = $qnum . "-" . $i;
$title = $json[$area]['title'];
$control = $area;
print "<p>" . $json[$area]['overview'] . "</p>";
print "<ul class='ks-cboxtags'>\n";
while( $i < 9) {
  //$infoButton = '<i class="fa-solid fa-circle-info"></i>';
  $summary= $i . '-summary';
   ## If a summary in there, use it as a tooltip
  if ($json[$area][$summary] != "") {
  
  ## Construct the info button
  #$itemSummary = '<p>This is the first paragraph. <em class="icon-question opener" data-id="' . $infoId . '" style="cursor: pointer;"> Help 1</em></p><div class="dialog_help" id="' . $infoId . '" title="Dialog 1 title"> <p>Dialog Help Content One.</p>	</div>';

  $itemSummary = '&nbsp; <i class="fa-solid fa-circle-info" style="display: inline-block;max-width: 100px;" title="' . $json[$area][$summary] . '"></i>';
  } else {
    $itemSummary = "";
  }
  $tier = $i . '-tier';
  $tierClass = "smallText" . $json[$area][$tier];
  $points = $i . "-points";
  print '<li><input type="checkbox" name="' . "control" . $qnum . "-" . $i . "\" id=\"" . "control" . "$qnum" . "-" . $i . '" value="' . $json[$area][$points] . '"><label for="' . "control" . $qnum . "-" . $i . '"><p class="' . $tierClass. '">'  . $json[$area][$tier] . '</p>' . $json[$area][$i] . "$itemSummary &nbsp </label></li>". "\n";
  $i++;
}
print "</ul>";
}
?>
<div class="tab">
  <div id="centerDivLine">
<h2>Profile: <?php echo $profile;?> </h2>

</div>
<?php
$first=0;
foreach ($controls as $control) {
	$title = $json[$control]['title'];
  if ($first < 2) {
	  print '<button class="tablinks" onclick="openCity(event, \'' . $control . '\')" id="defaultOpen">' . $title .'</button>';
  } else {
	  print '<button class="tablinks" onclick="openCity(event, \'' . $control . '\')">' . $title .'</button>';

  }
$first++;
}
?>  

</div>
</div>

<div class="container">
<form action="results.php">
  
<fieldset>
<!-- Tab content -->
<?php
foreach ($controls as $control) {
print '<div id="' . $control . '" class="tabcontent">';
getControls($control,$json);
print '</div>';
}
?>
  </fieldset>
  <br>
  <input type="hidden" name="profile" value="<?php echo $profile;?>">
  <div id="centerDivLine">
  <?php
## Compliance Frameworks
#$frameworks = array("NIST 800-53","ISO 27001","PCI DSS","FedRAMP","Common Criteria");
$stringFrameworks = file_get_contents("compliance.json");
$jsonFrameworks = json_decode($stringFrameworks, true);
#print_r($jsonFrameworks);
## Add checklist for compliance frameworks
print '<div class="form-group horizontal-checkboxes">
<p class="smallTextFramework">To which of the following frameworks do you have to adhere?</p>';
foreach ($jsonFrameworks as $framework) {
  print "<input id='" . $framework['name'] . "' name='framework[]' value='" . $framework['name'] . "' type='checkbox'>&nbsp <label class='smallTextFramework'  id='" . $framework['name'] . "' for='framework'>" . $framework['name'] . "</label>&nbsp &nbsp";
  print "<input id='" . $framework['link'] . "' name='frameworkLink[]' value='" . $framework['link'] . "' type='hidden'>";
}

print '</div>';
?>
</div>
<input class='ui-button ui-widget ui-corner-all' id='submitButton' type='submit' name='Submit' value='Submit'>
</form>
</div>

<script type="text/javascript" >
function openCity(evt, cityName) {
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