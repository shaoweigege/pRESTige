<?php

//GET PASSWORD FROM Codiad Settings
$users_file = '../../ide/data/users.php';
$reference_obj = null;

function fill_reference_obj($users_file){
  global $reference_obj;
	$data = file_get_contents($users_file);
	$startpos = strpos($data, "[");
	$endpos = strrpos($data, "]");
	$len = $endpos - $startpos + 1;
	$realdata = substr($data, $startpos, $len);
	$obj = (array) json_decode($realdata, true);
	$reference_obj = $obj[0];
}

fill_reference_obj($users_file);

function get_password(){
  global $reference_obj;
	return $reference_obj['password'];	
}

function get_username(){
  global $reference_obj;
	return $reference_obj['username'];	
}


$PASSWORD = get_password();
$USERNAME = get_username();
$SUPPLIED_PASSWORD = $_POST["password"];
$SUPPLIED_PASSWORD_ENC = sha1(md5($SUPPLIED_PASSWORD));
$SUPPLIED_USERNAME = $_POST["username"];

if($SUPPLIED_PASSWORD_ENC == $PASSWORD && $SUPPLIED_USERNAME == $USERNAME){
  //include("configure.php");
  //exit();
} else {
  header('Location: .?auth=false');
  exit();
}

?>
<head>
  <title>pRESTige Setup</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  		<!--Append / at the end of URL to load everything properly -->
		<script>
		window.onload = function(){
			// var location = "" + window.location;
			// if(location.charAt(location.length-1) !== '/'){
			//   if(!(location.indexOf('?') > -1)){
  	// 			var newLocation = location + "/";
  	// 			window.location = newLocation;
			//   }
			// }
			// if(("" + window.location).indexOf('configure/') > -1){
			//   	var configForm = document.getElementById('configForm');
   //     			configForm.action = configForm.action.replace("configure/","");
			// }
			
			// var urlParams = new URLSearchParams(location.search);
			// var auth = urlParams.get('auth');
			// if(location.search("auth=false") > -1){
			//   $('#error').text("Invalid Credentials!");
			// }

		}
		
		</script>
		
		<style type="text/css">
		    .main-container {
                margin: auto;
                width: 40%;
                margin-top: 100px;
            }
            .center-text{
                text-align: center;
            }
		</style>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
	<script type="text/javascript">
		$(function(){
			$('#host').focus();
		})
	</script>  
</head>
<body>
<div class="panel panel-primary main-container">
  <div class="panel-heading center-text">pRESTige Configuration - Provide MySQL Connection</div>
  <div class="panel-body">
		<form id='configForm' action="generate-config.php" method="post">
		    <div class="form-group">
		      <label for="host">Host:</label>
		      <input type="text" class="form-control" id="host" placeholder="Enter hostname" name="host">
		    </div>
		    <div class="form-group">
		      <label for="user">User:</label>
		      <input type="text" class="form-control" id="user" placeholder="Enter username" name="user" required>
		    </div>
		    <div class="form-group">
		      <label for="pwd">Password:</label>
		      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
		    </div>
		    <div class="form-group">
		      <label for="database">Database:</label>
		      <input type="text" class="form-control" id="database" placeholder="Enter database name" name="database" required>
		    </div>
		    <button type="submit" class="btn btn-default">Submit</button>
		  </form>
  </div>
</div>
</body>
</html>



  


