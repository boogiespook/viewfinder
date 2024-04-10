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


<script type="text/javascript" >
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}
</script>



</head>

<body>
  <header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <div class="pf-c-page__header-brand-toggle">
                  </div>
                  <a class="pf-c-page__header-brand-link" href="index.php">
                  <img class="pf-c-brand" src="images/viewfinder-banner.png" alt="Viewfinder logo" />
                  </a>
                </div>
</header>
<div class="container">
<?php
$string = file_get_contents("controls.json");
$json = json_decode($string, true);

function getTierColor($tier) {


}

function getControls ($area,$json) {

$i=1;
$qnum = $json[$area]['qnum'];
$title = $json[$area]['title'];
$control = $area;
print "<p class='overview'>" . $json[$area]['overview'] . "</p>";
print "<ul class='ks-cboxtags'>\n";
while( $i < 9) {
  $points= $i . '-summary';
  $tier = $i . '-tier';
  $tierClass = "smallText" . $json[$area][$tier];
  print '<li><input type="checkbox" name="' . "control" . $qnum . "-" . $i . "\" id=\"" . "control" . "$qnum" . "-" . $i . '" value="' . $i . '"><label for="' . "control" . $qnum . "-" . $i . '"><p class="' . $tierClass. '">'  . $json[$area][$tier] . '</p>' . $json[$area][$i] . '&nbsp </label></li>'. "\n";
  $i++;
}
print "</ul>";
}
?>

<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'SecureInfrastructure')" id="defaultOpen">Secure Infrastructure</button>
  <button class="tablinks" onclick="openCity(event, 'SecureData')">Secure Data</button>
  <button class="tablinks" onclick="openCity(event, 'SecureIdentity')">Secure Identity</button>
  <button class="tablinks" onclick="openCity(event, 'SecureApplication')">Secure Application</button>
  <button class="tablinks" onclick="openCity(event, 'SecureNetwork')">Secure Network</button>
  <button class="tablinks" onclick="openCity(event, 'SecureRecovery')">Secure Recovery</button>
  <button class="tablinks" onclick="openCity(event, 'SecureOperations')">Secure Operations</button>
</div>
</div>
<div class="container">
<form action="plot.php">
<fieldset>
<!-- Tab content -->
<div id="SecureInfrastructure" class="tabcontent">
<?php
getControls("SecureInfrastructure",$json)

#getCriteria("1",$db);
?>
</div>

<div id="SecureData" class="tabcontent">
<?php
getControls("SecureData",$json)

#getCriteria("2",$db);
?></div>

<div id="SecureIdentity" class="tabcontent">
<?php
#getCriteria("3",$db);
getControls("SecureIdentity",$json)

?>
</div>

<div id="SecureApplication" class="tabcontent">
<?php
#getCriteria("4",$db);
getControls("SecureApplication",$json)

?></div>

<div id="SecureNetwork" class="tabcontent">
<?php
#getCriteria("5",$db);
getControls("SecureNetwork",$json)

?>
</div>

<div id="SecureRecovery" class="tabcontent">
<?php
#getCriteria("6",$db);
getControls("SecureRecovery",$json)

?>
</div>

<div id="SecureOperations" class="tabcontent">
<?php
#getCriteria("7",$db);
getControls("SecureOperations",$json)

?>
</div>
  </fieldset>
  <br>
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