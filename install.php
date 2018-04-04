<!DOTYPE html>
<html>
	<head>
		<title>chromeGamification || setup</title>
		<style>
body {font-family: Arial;}

/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab div {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab div:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab div.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabContent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}


			/* The Modal (background) */
		.modal {
			display: block; /* Hidden by default */
			position: fixed; /* Stay in place */
			z-index: 1; /* Sit on top */
			padding-top: 100px; /* Location of the box */
			left: 0;
			top: 0;
			width: 100%; /* Full width */
			height: 100%; /* Full height */
			overflow: auto; /* Enable scroll if needed */
			background-color: rgb(0,0,0); /* Fallback color */
			background-color: rgba(0,0,0,0.4); /* Black w/ opatab */
		}
		
		/* Modal Content */
		.modal-content {
			position: relative;
			background-color: #fefefe;
			margin: auto;
			padding: 0;
			border: 1px solid #888;
			width: 80%;
			box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
			-webkit-animation-name: animatetop;
			-webkit-animation-duration: 0.4s;
			animation-name: animatetop;
			animation-duration: 0.4s
		}
		
		/* Add Animation */
		@-webkit-keyframes animatetop {
			from {top:-300px; opatab:0} 
			to {top:0; opatab:1}
		}
		
		@keyframes animatetop {
			from {top:-300px; opatab:0}
			to {top:0; opatab:1}
		}
		
		/* The Close Button */
		.close {
			color: white;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}
		
		.close:hover,
		.close:focus {
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}
		
		.modal-header {
			padding: 2px 16px;
			background-color: #5cb85c;
			color: white;
		}
		
		.modal-body {padding: 2px 16px;}
		
		.modal-footer {
			padding: 2px 16px;
			background-color: #5cb85c;
			color: white;
		}
		</style>
		<script>
			function calcLevels()
			{
				var min = document.getElementById('startXP').value
				if((document.getElementById('XPIncrement').value) == "exponential")
				{
					var x = 0;
					for(x = 1; x <= 5; x++)
					{
						var xp = min * Math.pow(x,2);
						var xString = x.toString();
						var id = "level"+xString+"XP";
						document.getElementById(id).innerHTML = xp;
					}
				}
				else
				{
					var x = 0;
					for(x = 1; x <= 5; x++)
					{
						var xp = min * x;
						var xString = x.toString();
						var id = "level"+xString+"XP";
						document.getElementById(id).innerHTML = xp;
					}
				}
			}
			function outputBuffer(output)
			{
				document.getElementById('outputBuffer').innerHTML= output;
			}
		</script>
	</head>
	<body>
<?php
	include "dependancies/custom.php";
	if(count($_POST) > 0)//install
	{
		?>
		<!--output buffer based on the modal tutorial found at https://www.w3schools.com/howto/howto_css_modals.asp-->
		<!-- The Modal -->
		<div id="myModal" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<h2>What's Happening?</h2>
				</div>
				<div class="modal-body">
					<div id="outputBuffer">
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
		<?php
		//add encrpytor support in custom.php
		{
			output("adding encryptor support to custpom.php");
			$custom = file_get_contents("dependancies/custom.php");
			$custom = str_replace("?>","	include \"encryptor/encrypt.php\";\r\n?>",$custom);
			file_put_contents("dependancies/custom.php",$custom);
		}
		//change passwordAlgorithm
		{
			output("Modifying Encryption Script");
			$encryptor = file_get_contents("dependancies/encryptor/encrypt.php");
			$encryptor = str_replace("\$passwordHash","\"" . urlencode(htmlspecialchars($_POST["passwordHash"])) . "\"",$encryptor);
			file_put_contents("dependancies/encryptor/encrypt.php",$encryptor);
		}
		// create Databases
		{
			output("creating Databases");
			//see if databases have been created
			{
				$databases = $dataClient->listDatabases();
				if(!(in_array($dataDB,$databases)))
				{
					$dataClient -> createDatabase();
				}
				if(!(in_array($configDB,$databases)))
				{
					$configClient -> createDatabase();
				}
			}
		}
		//store admin details in database
		{
			output("Saving your Details in the Database");
			include "dependancies/encryptor/encrypt.php";
			error_reporting(!E_WARNING);//disable warnings as PHPthrows warning when creating objects from empty values
			$administrator = new stdClass();
			$administrator -> _id = urlencode(htmlspecialchars($_POST["adminUser"]));
			$administrator -> pass = encrypt($_POST["adminPassword"]);
			$administrator -> security ="Administrator";
			$administrator -> details -> fullName = ($_POST["userFullName"]);
			$administrator -> scores -> positive = 0;
			$administrator -> scores -> Neutral = 0;
			$administrator -> scores -> negative = 0;
			$dataClient -> storeDoc($administrator);//save to database
		}
		//configure configuration
		{
			output("Saving configuration settings to database");
			$levels = new StdClass();
			$badges = new StdClass();
			$activity = new stdClass();
			$badges -> _id = "badges";
			$levels -> _id = "levels";
			$activity -> _id = "activity";
			$formula = $_POST["XPIncrement"];
			$level1XP = $_POST["startXP"];
			if($_POST["populate"] == "on")//user opted for pre-populated data
			{
				$levels -> levels -> Saffron -> levelName = "Saffron";
				$levels -> levels -> Gold -> levelName = "Gold";
				$levels -> levels -> Rhodium -> levelName = "Rhodium";
				$levels -> levels -> Platinum -> levelName = "Platinum";
				$levels -> levels -> Plutonium -> levelName = "Plutonium";
				$levels -> levels -> Painite -> levelName = "Painite";
				$levels -> levels -> Taaffeite -> levelName = "Taaffeite";
				$levels -> levels -> Tritium -> levelName = "Tritium";
				$levels -> levels -> Diamond -> levelName = "Diamond";
				$levels -> levels -> californium -> levelName = "californium";
				$levels -> levels -> Antimatter -> levelName = "Antimatter";
				$levels -> levels -> Saffron -> levelNumber = 1;
				$levels -> levels -> Gold -> levelNumber = 2;
				$levels -> levels -> Rhodium -> levelNumber = 3;
				$levels -> levels -> Platinum -> levelNumber = 4;
				$levels -> levels -> Plutonium -> levelNumber = 5;
				$levels -> levels -> Painite -> levelNumber = 6;
				$levels -> levels -> Taaffeite -> levelNumber = 7;
				$levels -> levels -> Tritium -> levelNumber = 8;
				$levels -> levels -> Diamond -> levelNumber = 9;
				$levels -> levels -> californium -> levelNumber = 10;
				$levels -> levels -> Antimatter -> levelNumber = 11;
				$levels -> levels -> Saffron -> progressColor = "#FFB001";
				$levels -> levels -> Gold -> progressColor = "#EFE279";
				$levels -> levels -> Rhodium -> progressColor = "#B9B4A1";
				$levels -> levels -> Platinum -> progressColor = "#969696";
				$levels -> levels -> Plutonium -> progressColor = "#AE90D0";
				$levels -> levels -> Painite -> progressColor = "#D18184";
				$levels -> levels -> Taaffeite -> progressColor = "#D6ABC9";
				$levels -> levels -> Tritium -> progressColor = "#5195f5";
				$levels -> levels -> Diamond -> progressColor = "#FFFFFF";
				$levels -> levels -> californium -> progressColor = "#b03c7c";
				$levels -> levels -> Antimatter -> progressColor = "#c2a2c7";
				if($formula == "exponential")
				{
					$levels -> levels -> Saffron -> maxXP = $level1XP * pow(($levels -> levels -> Saffron -> levelNumber),2);
					$levels -> levels -> Gold -> maxXP = $level1XP * pow(($levels -> levels -> Gold -> levelNumber),2);
					$levels -> levels -> Rhodium -> maxXP = $level1XP * pow(($levels -> levels -> Rhodium -> levelNumber),2);
					$levels -> levels -> Platinum -> maxXP = $level1XP * pow(($levels -> levels -> Platinum -> levelNumber),2);
					$levels -> levels -> Plutonium -> maxXP = $level1XP * pow(($levels -> levels -> Plutonium -> levelNumber),2);
					$levels -> levels -> Painite -> maxXP = $level1XP * pow(($levels -> levels -> Painite -> levelNumber),2);
					$levels -> levels -> Taaffeite -> maxXP = $level1XP * pow(($levels -> levels -> Taaffeite -> levelNumber),2);
					$levels -> levels -> Tritium -> maxXP = $level1XP * pow(($levels -> levels -> Tritium -> levelNumber),2);
					$levels -> levels -> Diamond -> maxXP = $level1XP * pow(($levels -> levels -> Diamond -> levelNumber),2);
					$levels -> levels -> californium -> maxXP = $level1XP * pow(($levels -> levels -> californium -> levelNumber),2);
					$levels -> levels -> Antimatter -> maxXP = $level1XP * pow(($levels -> levels -> Antimatter -> levelNumber),2);
				}
				else
				{
					$levels -> levels -> Saffron -> maxXP = $level1XP * ($levels -> levels -> Saffron -> levelNumber);
					$levels -> levels -> Gold -> maxXP = $level1XP * ($levels -> levels -> Gold -> levelNumber);
					$levels -> levels -> Rhodium -> maxXP = $level1XP * ($levels -> levels -> Rhodium -> levelNumber);
					$levels -> levels -> Platinum -> maxXP = $level1XP * ($levels -> levels -> Platinum -> levelNumber);
					$levels -> levels -> Plutonium -> maxXP = $level1XP * ($levels -> levels -> Plutonium -> levelNumber);
					$levels -> levels -> Painite -> maxXP = $level1XP * ($levels -> levels -> Painite -> levelNumber);
					$levels -> levels -> Taaffeite -> maxXP = $level1XP * ($levels -> levels -> Taaffeite -> levelNumber);
					$levels -> levels -> Tritium -> maxXP = $level1XP * ($levels -> levels -> Tritium -> levelNumber);
					$levels -> levels -> Diamond -> maxXP = $level1XP * ($levels -> levels -> Diamond -> levelNumber);
					$levels -> levels -> californium -> maxXP = $level1XP * ($levels -> levels -> californium -> levelNumber);
					$levels -> levels -> Antimatter -> maxXP = $level1XP * ($levels -> levels -> Antimatter -> levelNumber);
				}
				$badges -> badges -> MovingOnUp -> badgeName = "Moving On Up";
				$badges -> badges -> MovingOnUp -> awardedAt = $level1XP;
				$badges -> badges -> MovingOnUp -> description = "Awarded for passing Level 1";
				$badges -> badges -> MovingOnUp -> badgeImage = "badge_MovingOnUp.png";
				$badges -> badges -> EagerBeaver -> badgeName = "Eager Beaver";
				$badges -> badges -> EagerBeaver -> awardedAt = 5000;
				$badges -> badges -> EagerBeaver -> description = "Awarded for earning 5000 XP";
				$badges -> badges -> EagerBeaver -> badgeImage = "badge_EagerBeaver.png";
				$badges -> badges -> PersistenceisKing -> badgeName = "Persistence is King";
				$badges -> badges -> PersistenceisKing -> awardedAt = 10000;
				$badges -> badges -> PersistenceisKing -> description = "Awarded for earning 10000 XP";
				$badges -> badges -> PersistenceisKing -> badgeImage = "badge_PersistenceisKing.png";
				$specialBadgeID = "There'sNoTurningBack";
				$badges -> badges -> $specialBadgeID -> badgeName = "There's No Turning Back";
				$badges -> badges -> $specialBadgeID -> awardedAt = 20000;
				$badges -> badges -> $specialBadgeID -> description = "Awarded for earning 20 000 XP";
				$badges -> badges -> $specialBadgeID -> badgeImage = "badge_There'sNoTurningBack.png";
				$badges -> badges -> Dare -> badgeName = "Dare";
				$badges -> badges -> Dare -> awardedAt = 50000;
				$badges -> badges -> Dare -> description = "♬♬You've Got to push in on, you. You've got to think it. That's what you do baby, Hold it down there♬♬";
				$badges -> badges -> Dare -> badgeImage = "badge_Dare.png";
				$activity -> positive -> github -> name = "github";
				$activity -> positive -> github -> browse = 5;
				$activity -> positive -> github -> click = 1;
				$activity -> positive -> github -> typing = 3;
				$activity -> positive -> github -> scroll = 0.1;
				$activity -> negative -> youtube -> name = "youtube";
				$activity -> negative -> youtube -> browse = 5;
				$activity -> negative -> youtube -> click = 1;
				$activity -> negative -> youtube -> typing = 3;
				$activity -> negative -> youtube -> scroll = 0.1;
			}
			else
			{
				$levels -> levels -> StartingOut -> levelName = "Starting Out";
				$levels -> levels -> StartingOut -> levelNumber = 1;
				$levels -> levels -> StartingOut -> progressColor = "lightGrey";
				if($formula == "exponential")
				{
					$levels -> levels -> StartingOut -> maxXP = $level1XP * pow(($levels -> levels -> StartingOut -> levelNumber),2);
				}
				else
				{
					$levels -> levels -> StartingOut -> maxXP = $level1XP * ($levels -> levels -> StartingOut -> levelNumber);
				}
			}
			$levels -> formula = $_POST["XPIncrement"];
			$levels -> level1XP = $_POST["startXP"];
		}
		$configClient -> storeDoc($levels);
		$configClient -> storeDoc($badges);
		$configClient -> storeDoc($activity);
		//make installed.txt to verify installation
		{
			fopen("installed.txt","w");
		}
		//make game directories
		{
			mkdir("game");
			mkdir("game/badges");
		}
		echo "<script>window.alert('Instalation Completed Successfully');window.close();</script>";
	}
	else//get form Data
	{
?>
		<h1>INSTALL chromeGamification</h1>
		<p> Need help? Hover over each input field or option to see what it does.</p>
		<form method="post">
			<div class="tab">
				<div id="defaultOpen" class="tablinks" onclick="openTab(event, 'GeneralSettings')">General Settings*</div>
				<div class="tablinks" onclick="openTab(event, 'YourInformation')">Your Information*</div>
				<div class="tablinks" onclick="openTab(event, 'GamificationSettings')">Gamification Settings*</div>
			</div>
			
			<div id="GeneralSettings" class="tabContent">
				<label for="adminUser">Administrator Email:</label>
				<input title="Provide your email address, This will be used to create your user in Couch DB" name="adminUser" type="email" placeholder="someone@example.com" required>*<br>
				<label for="adminPassword">Administrator Password:</label>
				<input title="Create a password to use with your email address" name="adminPassword" type="password" placeholder="password" required>*<br>
				<label for="passwordHash">Password Hash:</label>
				<input title="A custom string which will be used in encrypting your passwords" name="passwordHash" type="text" placeholder="@nyth1ngYouCan Think0f" required>*
			</div>
			
			<div id="YourInformation" class="tabContent">
				<label for="fullName">Full Name</label>
				<input title="Enter your Full Name, This will be used for your gamification Profile" name="userFullName" type="text" placeholder="Joe Soap" required>*<br>
			</div>
			
			<div id="GamificationSettings" class="tabContent">
				<label for="populate">Populate with demo Data?</label>
				<input id="populate" type="checkbox" name="populate" title"Selecting this option will pre populate your gamification config with 10 levels, you can always add more levels at a later stage"><br>
				<label for="startXP">First Level Max XP</label>
				<input min="1" id="startXP" onkeyup="calcLevels();" onclick="calcLevels();" value="1000" name="startXP" type="number" required title="The value of xp required before you level up past the first level">*<br>
				<label for="XPIncrement">How do you wish to increment your XP?</label>
				<select id="XPIncrement" name="XPIncrement" onclick="calcLevels();">
					<option title="Each level requires more and more XP to level up, which can result in loss of interest the further you progress" value="exponential">Exponentially</option>
					<option title="Each level requires the same XP to level up" value="linear">Linearly</option>
				</select>*<br>
				<div title="Change the values in 'First level max XP' and 'xp incrementation' to see the effects">
					<p>Level 1 requires <span id="level1XP"></span> XP to level up</p>
					<p>Level 2 requires <span id="level2XP"></span> XP to level up</p>
					<p>Level 3 requires <span id="level3XP"></span> XP to level up</p>
					<p>Level 4 requires <span id="level4XP"></span> XP to level up</p>
					<p>Level 5 requires <span id="level5XP"></span> XP to level up</p>
					<p>and so on</p>
				</div>
			</div>
			<input name="submit" type="submit" value="Install">
		</form>
	<script>
	calcLevels();
	function openTab(evt, tabName) {
	    var i, tabContent, tablinks;
	    tabContent = document.getElementsByClassName("tabContent");
	    for (i = 0; i < tabContent.length; i++) {
	        tabContent[i].style.display = "none";
	    }
	    tablinks = document.getElementsByClassName("tablinks");
	    for (i = 0; i < tablinks.length; i++) {
	        tablinks[i].className = tablinks[i].className.replace(" active", "");
	    }
	    document.getElementById(tabName).style.display = "block";
	    evt.currentTarget.className += " active";
	}
	
	// Get the element with id="defaultOpen" and click on it
	document.getElementById("defaultOpen").click();
	</script>
	</body>
</html>
<?php
	}
?>
