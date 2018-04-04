<?php
	//determine whether app has been installed
	if(is_file("installed.txt"))
	{
		//declare dependancies
		{
			error_reporting(!E_WARNING);//disables error reporting
			include "dependancies/custom.php";//most variables are initialized in this script in order to keep this file cleaner
			//include "dependancies/encryptor/encrypt.php";
			session_start();//required to use session logging, which is used to track the user
			$pageURL = "";//empty placeholder
			//determine page URL for login
			{
    			//ssl Verification
    			if($_SERVER["HTTPS"] == "on")
    			{
    				$pageURL .= "https://";
    			}
    			else//page loaded over http:
    			{
    				$pageURL .= "http://";
    			}
    			$pageURL .= $_SERVER["SSL_TLS_SNI"];
    			$pageURL .= $_SERVER["REQUEST_URI"];
			}
		}
		?>
<!DOCTYPE html>
<html>
	<head>
		<title>Config || chromeGamification</title>
		<style>
body {font-family: Arial;}
table
{
	width:100%;
	border:1px solid black;
	border-collapse:collapse;
	table-layout:fixed;
}
th
{
	border:1px solid black;
	border-collapse:collapse;
}
td
{
	cursor:pointer;
	border:1px solid black;
	border-collapse:collapse;
}
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
.editContent {
    display: block;
    padding: 6px 12px;
    border: 1px solid #ccc;
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
.addButton
{
	border-width: 2px;
    border-style: outset;
    border-color: buttonface;
    border-image: initial;
	background-color:blue;
	text-align:center;
	margin:10px auto;
	width:30%;
	padding:10px 0;
	cursor:pointer;
	color:white;
	font-size:20px;
	box-sizing: border-box;
}
.cancelButton
{
	border-width: 2px;
    border-style: outset;
    border-color: buttonface;
    border-image: initial;
	background-color:grey;
	text-align:center;
	width:30%;
	padding:10px 0;
	cursor:pointer;
	color:white;
	font-size:20px;
	display: inline-block;
    box-sizing: border-box;
}
.saveButton
{
	background-color:green;
	text-align:center;
	width:30%;
	padding:10px 0;
	cursor:pointer;
	color:white;
	font-size:20px;
}
		</style>
		<script>
function addMatchPattern()//function used to add match patterns  foe web activity
{
	var blocks = document.getElementsByClassName('block');//the container that houses match patterns
	var length = blocks.length;//determine the number of blocks
	var newBlock = "<div class=\"block\"><h2>New Match Pattern</h2><input type=\"hidden\" name=\"" + length +"[new]\" value=\"true\"><label for=\"" + length +"[match]\">URL contains:</label><input type=\"text\" name=\"" + length +"[match]\" required>*<br><label for=\"" + length +"[browse]\">Points for browsing:</label><input required=\"\" title=\"intervals of 0.01\" type=\"number\" step=\"0.01\" name=\"" + length +"[browse]\" value=\"1\">*<br><label for=\"" + length +"[click]\">Points for clicking:</label><input required=\"\" title=\"intervals of 0.01\" type=\"number\" step=\"0.01\" name=\"" + length +"[click]\" value=\"1\">*<br><label for=\"" + length +"[typing]\">Points for typing:</label><input required=\"\" title=\"intervals of 0.01\" type=\"number\" step=\"0.01\" name=\"" + length +"[typing]\" value=\"1\">*<br><label for=\"" + length +"[scroll]\">Points for scrolling:</label><input required=\"\" title=\"intervals of 0.01\" type=\"number\" step=\"0.01\" name=\"" + length +"[scroll]\" value=\"0\">*<br></div>";//inner html of new block
	if(length == 0)//there are no match patterns saved, so a brand new block needs to be added
	{
		var parent = document.getElementsByTagName('form')[0].children[0];
		parent.innerHTML = newBlock;
	}
	else// there are match patterns so add a new block to the existing lot
	{
		var parent = blocks[0].parentElement;
		var current = parent.innerHTML;
		parent.innerHTML = current + newBlock;
	}
};
function changePassword()//enables the password field so that a users password can be changed
{
	var field = document.getElementById('pass');
	field.type= "password";
	field.required = true;
	field.minLength = "8";
	field.innerText= "*";
};
function changeBadgeImage()//method to allow new image forbadges
{
	var checkbox = document.getElementById('changeImage');
	var fileToUpload = document.getElementById('fileToUpload');
	if(checkbox.checked)
	{
		fileToUpload.disabled = false;
	}
	else
	{
		fileToUpload.disabled = true;
	}
}
		</script>
	</head>
		<?php
		if((is_null($_SESSION["chromeGamification_user"])))//validates if user has signed in
		{
			echo"<script>window.alert(\"You are not signed in. use the popup to sign in, and try again \");</script>";
		}
		else//user is signed in
		{
			//test if user is admin
			$userData = $dataClient -> getDoc($_SESSION["chromeGamification_user"]);//retrieve user info from server
			if(!($userData -> security == "Administrator"))//user is not admin
			{
				echo "<script>window.alert(\"You are not authorised to view this page, Please contact your adminitrator to resolve the issue\");</script>";
			}
			elseif(count($_GET)==0)//no GET headers have been requested so display the config root page
			{
				?>
	<body>
		<h1>chromeGamification configuration</h1>
			<div class="tab">
				<div id="defaultOpen" class="tablinks" onclick="openTab(event, 'Users')">Users</div>
				<div class="tablinks" onclick="openTab(event, 'Levels')">Levels</div>
				<div class="tablinks" onclick="openTab(event, 'Badges')">Badges</div>
				<div class="tablinks" onclick="openTab(event, 'ActivityScoring')">Activity Scoring</div>
			</div>
			
			<div id="Users" class="tabContent">
				<table>
					<thead>
						<tr>
							<th>User</th>
							<th>Email</th>
							<th>Security</th>
							<th>Score</th>
							<th>Remove?</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$allUsers = $dataClient -> getAllDocs();//shows all documents saved in the gamification Data DB,all data is shown in the rows object
							//loop through the rows object to get each user ID
							foreach($allUsers -> rows as $currentRow)
							{
								$id = $currentRow -> id;//the userID
								$currentUser = $dataClient -> getDoc($id);//all user data
								$fullName = $currentUser -> details -> fullName;
								$email = urldecode($currentUser -> _id);//the email address of the user in normal format as ID's are saved in URL friendly format
								$security = $currentUser -> security;//the user security group
								$score = ($currentUser -> scores -> positive) + ($currentUser -> scores -> negative);
								if($security == "Administrator")//Administrators cannot be removed
								{
									$remove = "<td>not allowed</td>";
								}
								else
								{
									$remove = "<td style=\"text-align:center\" onclick=\"window.location.href='?remove=users&id=".$id ."';\">&#10060;</td>";
								}
								echo"<tr><td onclick=\"window.location.href='?edit=users&id=" . $id ."';\">$fullName</td><td onclick=\"window.location.href='?edit=users&id=" . $id ."';\">$email</td><td onclick=\"window.location.href='?edit=users&id=" . $id ."';\">$security</td><td onclick=\"window.location.href='?edit=users&id=" . $id ."';\">$score</td>$remove</tr>";
							}
						?>
					</tbody>
				</table>
				<div class="addButton" onClick="window.location.href='?edit=users&id=new';">Add New User</div>
			</div>
			
			<div id="Levels" class="tabContent">
				<table>
					<thead>
						<tr>
							<th>Level Name</th>
							<th>Max XP</th>
							<th>Remove?</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sort = array();// a blank array, used to sort levels by levelNumber
							$levels = $configClient -> getDoc("levels");//retrieve Levels list from the config
							//loop through each level
							foreach($levels ->levels as $currentLevel)
							{
								$levelName = $currentLevel -> levelName;
								$levelNumber = $currentLevel -> levelNumber;
								$sort[$levelNumber] = $levelName;//save XP to sort array
							}
							$totalLevels = count($sort);
							//loop through sort
							for($x = 1; $x <= $totalLevels; $x ++)//use for loop instead of foreach so that the value of $x can be used to determine if level is allowed to be removed
							{
								$levelName = $sort[$x];
								$currentLevel = $levels -> levels -> $levelName;
								$maxXP = $currentLevel -> maxXP;
								$color = $currentLevel -> progressColor;
								if($x !== $totalLevels)//only the highest level can be removed
								{
									$remove = "<td title=\"Only the level with the Highest XP can be removed\">not allowed</td>";
								}
								else
								{
									$remove = "<td style=\"text-align:center\" onclick=\"window.location.href='?remove=levels&id=" . urlencode($levelName) . "';\">&#10060;</td>";
								}
								echo "<tr style=\"background-color:$color\"><td onclick=\"window.location.href='?edit=levels&id=" . urlencode($levelName) . "'\">$levelName</td><td onclick=\"window.location.href='?edit=levels&id=" . urlencode($levelName) . "'\">$maxXP</td>$remove</tr>";
							}
						?>
					</tbody>
				</table>
				<div class="addButton" onClick="window.location.href='?edit=levels&id=new';">Add New Level</div>
			</div>
			
			<div id="Badges" class="tabContent">
				<table>
					<thead>
						<tr>
							<th>Badge Name</th>
							<th>Badge Image</th>
							<th>Awarded at</th>
							<th>Remove?</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sort = array(); //empty array, this will be used to sort badges by the awarded XP
							$data = array(); //empty array, this contains the data for the badges
							$badges = $configClient -> getDoc("badges");//collect list of badges, the badges are stpred in a sub-object called badges
							//loop through badges to view the detains
							foreach($badges -> badges as $currentBadge)
							{
								if(!in_array($currentBadge -> awardedAt,$sort))//test if the awardedAt value is already in the sorting array
								{
									$sort[] = $currentBadge -> awardedAt;//add to sorting array
								}
								$data[$currentBadge -> awardedAt][] = str_replace(" ","",$currentBadge -> badgeName);// add badge ID to the data associated with the sort value
							}
							sort($sort);//sort the sort array in asscending order
							//loop through sort array
							foreach($sort as $currentSort)
							{
								$badgesAtXP = $data[$currentSort];//badges associated with the XP level
								sort($badgesAtXP);//sorts badges alphabetically
								//loop through the badges awarded at current awardedAt value
								foreach($badgesAtXP as $badgeID)
								{
									$currentBadge = $badges -> badges -> $badgeID;//collect badge details
									$badgeName = $currentBadge -> badgeName;
									$badgeImage = $currentBadge -> badgeImage;
									$awardedAt = $currentBadge -> awardedAt;
									if($currentBadge -> awarded)//only unawarded badges can be removed, however XP values can be changed but will not remove a badge from a user
									{
										$remove = "<td title=\"Only badges that have not been awarded can be removed, you can still edit the information of the badge, but once awarded, the badge cannot be unawarded\">not allowed</td>";
									}
									else
									{
										$remove = "<td onclick=\"location.href='?remove=badges&id=" . urlencode($badgeID) . "';\" style=\"text-align: center;\">&#10060;</td>";
									}
									echo "<tr><td onclick=\"location.href='?edit=badges&id=" . urlencode($badgeID) . "';\">$badgeName</td>";
									echo "<td onclick=\"location.href='?edit=badges&id=" . urlencode($badgeID) . "';\" style=\"text-align: center;\"><img src=\"game/badges/$badgeImage\" style=\"width: 100px;\"></td>";
									echo "<td onclick=\"location.href='?edit=badges&id=" . urlencode($badgeID) . "';\">$awardedAt</td>";
									echo "$remove</tr>";
								}
							}
						?>
					</tbody>
				</table>
				<div class="addButton" onClick="window.location.href='?edit=badges&id=new';">Add New Badge</div>
			</div>
			<div id="ActivityScoring" class="tabContent">
				<?php
					$activities = $configClient -> getDoc("activity");//get Data for activity scoring(match patterns)
					$positive  = $activities -> positive;
					$negative = $activities -> negative;
				?>
				<table>
					<thead>
						<tr>
							<th>Scoring Class</th>
							<th>Associated URL's</th>
						</tr>
					</thead>
					<tbody>
						<tr onclick ="window.location.href='?edit=activities&id=positive';">
							<td>Positive Scoring Sites</td>
							<td>
								<?php
								//loop through positive activity
									foreach($positive as $currentPositive)
									{
										$name = $currentPositive -> name;
										?>
									<table>
										<thead>
											<tr>
												<th colspan="2"><?php echo $name; ?></th>
											</tr>
											<tr>
												<th>Action</th>
												<th>Points</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Browsing</td>
												<td><?php echo $currentPositive -> browse; ?></td>
											</tr>
											<tr>
												<td>Clicking</td>
												<td><?php echo $currentPositive -> click; ?></td>
											</tr>
											<tr>
												<td>Typing</td>
												<td><?php echo $currentPositive -> typing; ?></td>
											</tr>
											<tr>
												<td>Scrolling</td>
												<td><?php echo $currentPositive -> scroll; ?></td>
											</tr>
										</tbody>
									</table>
										<?php
									}
								?>
							</td>
						</tr>
						<tr onclick ="window.location.href='?edit=activities&id=negative';">
							<td>Negative Scoring Sites</td>
							<td>
								<?php
								    //loop through negative activity
									foreach($negative as $currentNegative)
									{
										$name = $currentNegative -> name;
										?>
									<table>
										<thead>
											<tr>
												<th colspan="2"><?php echo $name; ?></th>
											</tr>
											<tr>
												<th>Action</th>
												<th>Points</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Browsing</td>
												<td><?php echo $currentNegative -> browse; ?></td>
											</tr>
											<tr>
												<td>Clicking</td>
												<td><?php echo $currentNegative -> click; ?></td>
											</tr>
											<tr>
												<td>Typing</td>
												<td><?php echo $currentNegative -> typing; ?></td>
											</tr>
											<tr>
												<td>Scrolling</td>
												<td><?php echo $currentNegative -> scroll; ?></td>
											</tr>
										</tbody>
									</table>
										<?php
									}
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
	<script>
	function openTab(evt, tabName)//script used to navigate tabs, modified from th Tabs tutorial at w3 schools: https://www.w3schools.com/howto/howto_js_tabs.asp
	{
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
			elseif(count($_POST) >0)//a form was submitted so saving/editing has been completed
			{
				if($_GET["edit"] == "users")//user is editing users
				{
					if($_POST["submit"] == "Save")//user saved their input
					{
						if($_GET["id"] == "new")//the user created a new user
						{
							$user = new stdClass;//declare user object for saving User Data
							$tamper = false;//declare anti tamper variable
							if((is_null($_POST["pass"])) || (strlen($_POST["pass"])< 8) ||(is_null($_POST["pass"]))||(is_null($_POST["email"]))||(is_null($_POST["fullName"]))||!(($_POST["security"] == "User")||($_POST["security"]=="Administrator")||($_POST["security"] == "Manager")))//validates that input was correct according to minimum requirements
							{
								$tamper = true;//the form was tampered with
							}
							$user -> _id = urlencode(htmlspecialchars($_POST["email"]));//the document ID for the user
							$user -> details -> fullName = $_POST["fullName"];
							$user -> pass = encrypt($_POST["pass"]);
							$user -> security = $_POST["security"];
							$user -> scores -> positive = 0;
							$user -> scores -> negative = 0;
							$user -> scores -> neutral = 0;
							$user -> badges = null;
							if($tamper)
							{
								invalidRequest();
							}
							else//do existence check
							{
								$allUsers = $dataClient -> getAllDocs();
								//loop through list of all user ID's
								foreach($allUsers -> rows as $currentRow)
								{
									if($user -> _id == $currentRow -> id)//user does exist
									{
										die("<script>window.alert('The user already exits, Please edit the existing and do not create the user again');window.location.href=\"config.php\";</script>");
									}
								}
								$dataClient -> storeDoc($user);//save user to database
								?>
<script>
	window.alert('User added successfully!');
	window.location.href="config.php";
</script>
								<?php
							}
						}
						else//user edited an existing User
						{
						$allUsers = $dataClient -> getAllDocs();
						$exists = false;//placeholder for existence check
						//do existence check
						{
							//loop throgh all users
							foreach($allUsers -> rows as $currentRow)
							{
								if(urlencode(htmlspecialchars($_GET["id"])) == $currentRow -> id)
								{
									$exists = true;
								}
							}
						}
						if($exists)
						{
							$user = $dataClient -> getDoc(urlencode(htmlspecialchars($_GET["id"])));
							$tamper = false;//placeholder for tamper testing
							if((is_null($_POST["pass"])) || (strlen($_POST["pass"])< 8) ||(is_null($_POST["pass"]))||(is_null($_POST["fullName"]))||!(($_POST["security"] == "User")||($_POST["security"]=="Administrator")||($_POST["security"] == "Manager")))
							{
								if(strlen($_POST["pass"]) == 0)//no Tamper
								{
									
								}
								else
								{
									$tamper = true; // user tampered with the form settings
								}
							}
							$user -> details -> fullName = $_POST["fullName"];
							if(strlen($_POST["pass"])){$user -> pass = encrypt($_POST["pass"]);}
							$user -> security = $_POST["security"];
							if($tamper)
							{
								invalidRequest();
							}
							else//Store in database
							{
								$dataClient -> storeDoc($user);//save changes to database
								?>
<script>
	window.alert('User updated successfully!');
	window.location.href="config.php";
</script>
								<?php
							}
						}
						else
						{
							invalidRequest();
						}

					}
					}
				}
				elseif($_GET["edit"] == "levels") // user is editing levels
				{
					if($_GET["id"] == "new")//user is adding a new level
					{
						//get maxXP and level number
						{
							$sort = array();//sorting array 
							$levels = $configClient -> getDoc("levels");
							$formula = $levels -> formula;//determines the formula used to calcuate max XP
							//loop through levels
							foreach($levels -> levels as $currentLevel)
							{
								$sort[] = $currentLevel -> levelNumber;
								if($_POST["levelName"] == $currentLevel -> levelName)//do existence check
								{
									die("<script>window.alert('That Level already exists, Please try again');window.location.href=\"config.php\";</script>");
								}
							}
							$totalLevels = count($sort);
							$levelNumber = $totalLevels +1;//determine the new level number
							$level1XP = $levels -> level1XP;//determines the XP required for level 1, used in the formula
							if($formula = "exponential")// do exponential XP algorithm
							{
								$maxXP = pow($levelNumber,2) * $level1XP;
							}
							else//do linear Algorithm
							{
								$maxXP = $levelNumber * $level1XP;
							}
							
						}
						$levelName = $_POST["levelName"];
						$levels -> levels -> $levelName -> levelName = $levelName;
						$levels -> levels -> $levelName -> maxXP = $maxXP;
						$levels -> levels -> $levelName -> levelNumber = $levelNumber;
						$levels -> levels -> $levelName -> progressColor = $_POST["color"];
						$configClient -> storeDoc($levels);//saves changes to database
						?>
<script>
	window.alert('Level added successfully');
	window.location.href="config.php";
</script>
						<?php
					}
					else//user is editing an existing level
					{
						$levels = $configClient -> getDoc("levels");
						$exists = false;
						foreach($levels -> levels as $currentLevel)
						{
							if($_GET["id"] == $currentLevel -> levelName)//do existence check
							{
								$exists = true;
							}
						}
						if(!$exists)
						{
							invalidRequest();
						}
						else//save Changes
						{
							$levelName = $_GET["id"];
							$levels -> levels -> $levelName -> progressColor = $_POST["color"];
							$configClient -> storeDoc($levels);// saves changes to database
							?>
<script>
window.alert('Level updated successfully');
window.location.href="config.php";
</script>
							<?php
						}
					}
				}
				elseif($_GET["edit"] == "badges")//user is editing badges
				{
					if($_GET["id"] == "new")//user is creating a new badge
					{
						//do existence check
						$exists = false;
						$badges = $configClient -> getDoc("badges");
						//loop through each badge
						foreach($badges -> badges as $currentBadge)
						{
							if(strtolower(str_replace(" ","",$currentBadge -> badgeName)) == strtolower(str_replace(" ","",$_POST["badgeName"])))
							{
								$exists = true;
							}
						}
						if(!$exists)
						{
							//handle image upload
							{
								$targetDirectory = "game/badges/";
								$badgeID = str_replace(" ","",$_POST["badgeName"]);
								$ext = substr($_FILES["fileToUpload"]["type"],(strpos($_FILES["fileToUpload"]["type"],"/")+1));
								$badgeImage = "badge_" . $badgeID . ".$ext";
								$targetFile = $targetDirectory . $badgeImage;
								$uploadOK = true;
								// Check if image file is a actual image or fake image
								$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
								if($check !== false)//image is real
								{
									$uploadOk = true;
								}
								else // image is fake
								{
									$uploadOk = false;
								}
								// Allow certain file formats
								$imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
								if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
								{
									echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
									$uploadOk = false;
								}
							}
							// Check if $uploadOk is set to 0 by an error
							if (!$uploadOk)
							{
								invalidRequest();
							}
							else//image can be uploaded
							{
								if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile))//file was uploaded successfully and database can be updated
								{
									$badges -> badges -> $badgeID -> badgeName = $_POST["badgeName"];
									$badges -> badges -> $badgeID -> awardedAt = $_POST["awardedAt"];
									$badges -> badges -> $badgeID -> description = $_POST["badgeDescription"];
									$badges -> badges -> $badgeID -> badgeImage = $badgeImage;
									$configClient -> storeDoc($badges);
									?>
<script>
	window.alert('Badge added successfully');
	window.location.href="config.php";
</script>
									<?php
								}
								else
								{
									invalidRequest();
							    }
							}
						}
						else//badge already exists
						{
							?>
<script>
	window.alert('That badge already exists,Try editing instead');
	window.location.href="config.php";
</script>
							<?php
						}
					}
					else//user is editing a badge
					{
						//do existence check
						$exists = false;
						$badges = $configClient -> getDoc("badges");
						//loop through each badge
						foreach($badges -> badges as $currentBadge)
						{
							if(strtolower(str_replace(" ","",$currentBadge -> badgeName)) == strtolower(str_replace(" ","",$_GET["id"])))
							{
								$exists = true;
							}
						}
						if($exists)
						{
							if($_POST["changeImage"] == "on")//user wants to change the image
							{
								//handle upload
								{
									$targetDirectory = "game/badges/";
									$badgeID = str_replace(" ","",$_GET["id"]);
									$ext = substr($_FILES["fileToUpload"]["type"],(strpos($_FILES["fileToUpload"]["type"],"/")+1));
									$badgeImage = "badge_" . $badgeID . ".$ext";
									$targetFile = $targetDirectory . $badgeImage;
									$uploadOK = true;
									// Check if image file is a actual image or fake image
									$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
									if($check !== false)//image is real
									{
										$uploadOk = true;
									}
									else // image is fake
									{
										$uploadOk = false;
									}
									// Allow certain file formats
									$imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
									if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
									{
										echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
										$uploadOk = false;
									}
								}
								// Check if $uploadOk is set to 0 by an error
								if (!$uploadOk)
								{
									invalidRequest();
								// if everything is ok, try to upload file
								}
								else
								{
									if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile))//file was uploaded successfully and database can be updated
									{
										$badges -> badges -> $badgeID -> awardedAt = $_POST["awardedAt"];
										$badges -> badges -> $badgeID -> description = $_POST["badgeDescription"];
										$badges -> badges -> $badgeID -> badgeImage = $badgeImage;
										$configClient -> storeDoc($badges);
										?>
<script>
	window.alert('Badge edited successfully');
	window.location.href="config.php";
</script>
										<?php
									}
									else
									{
										invalidRequest();
								    }
								}
							}
							else//user is not changing the image
							{
								$badgeID = str_replace(" ","",$_GET["id"]);
								$badges -> badges -> $badgeID -> awardedAt = $_POST["awardedAt"];
								$badges -> badges -> $badgeID -> description = $_POST["badgeDescription"];
								$configClient -> storeDoc($badges);
								?>
<script>
	window.alert('Badge edited successfully');
	window.location.href="config.php";
</script>
								<?php
							}
						}
						else
						{
							invalidRequest();
						}
					}
				}
				elseif($_GET["edit"] == "activities")//user is editing activities
				{
					//validate ID
					if($_GET["id"] == "positive" || $_GET["id"] == "negative")//ID is valid
					{
						$activity = $configClient -> getDoc("activity");
						$activityType = $_GET["id"];
						$activityClass = $activity -> $activityType;
						unset($activity -> $activityType);
						//loop throughh activities
						foreach($_POST as $currentReturn)
						{
							if(is_array($currentReturn))//validate if post field is for form data or save
							{
								//validate if enabled or new else discard
								if(isset($currentReturn["enabled"]))//keep this record
								{
									$match = $currentReturn["match"];
									$activity -> $activityType -> $match -> name = $match;
									$activity -> $activityType -> $match -> browse = floatval($currentReturn["browse"]);
									$activity -> $activityType -> $match -> click = floatval($currentReturn["click"]);
									$activity -> $activityType -> $match -> typing = floatval($currentReturn["typing"]);
									$activity -> $activityType -> $match -> scroll = floatval($currentReturn["scroll"]);
								}
								elseif(isset($currentReturn["new"]))
								{
									$match = $currentReturn["match"];
									$activity -> $activityType -> $match -> name = $match;
									$activity -> $activityType -> $match -> browse = floatval($currentReturn["browse"]);
									$activity -> $activityType -> $match -> click = floatval($currentReturn["click"]);
									$activity -> $activityType -> $match -> typing = floatval($currentReturn["typing"]);
									$activity -> $activityType -> $match -> scroll = floatval($currentReturn["scroll"]);
								}
							}
						}
						$configClient -> storeDoc($activity);
						?>
<script>
	window.alert('Activities updated successfully');
	window.location.href="config.php";
</script>
						<?php
					}
					else
					{
						invalidRequest();
					}
				}
				else
				{
					invalidRequest();
				}
			}
			else//no form submit, but an edit/remove has been called
			{
				error_reporting(!E_WARNING);
				//validate if edit is correct
				if($_GET["edit"] == "users")//edit a user
				{
					//validate user ID
					if($_GET["id"] == "new")
					{
						?>
	<body>
		<h1>Create New User</h1>
		<div class="editContent">
			<form method="post">
				<label for="fullName">Full Name:</label>
				<input name="fullName" type="text" required>*<br>
				<label for="email">Email:</label>
				<input name="email" type="email" required>*<br>
				<label for="security">Security:</label>
				<select required name="security">
					<option selected>User</option>
					<option>Manager</option>
					<option>Administrator</option>
				</select>*<br>
				<label for="pass">Password</label>
				<input type="password" name="pass" minLength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}" title="minimum 8 character, Must contain at least 1 Lowercase, 1 Uppercase & 1 special character" required>*<br>
				<div class="cancelButton" onClick="window.location.href='config.php';">Cancel</div><br>
				<input type="submit" name="submit" class="saveButton" value="Save">
			</form>
		</div>
	</body>
</html>
						<?php
					}
					else//do existence test
					{
						
						$allUsers = $dataClient -> getAllDocs();
						$exists = false;
						//loop through all users to retrieve the ID
						foreach($allUsers -> rows as $currentRow)
						{
							if(urlencode(htmlspecialchars($_GET["id"])) == $currentRow -> id)
							{
								$exists = true;
							}
						}
						
						if($exists)
						{
							error_reporting(E_ALL);
							$user = $dataClient -> getDoc(urlencode(htmlspecialchars($_GET["id"])));
							if($user -> security == "Administrator")
							{
								$securityOptions = "<option>User</option><option>Manager</option><option  selected>Administrator</option>";
							}
							elseif($user -> security == "Manager")
							{
								$securityOptions = "<option>User</option><option selected>Manager</option><option>Administrator</option>";
							}
							else
							{
								$securityOptions = "<option selected>User</option><option>Manager</option><option>Administrator</option>";
							}
							$fullName = $user -> details -> fullName;
							$email = $_GET["id"];
							?>
	<body>
		<h1>Edit User</h1>
		<div class="editContent">
			<form method="post">
				<label for="fullName">Full Name:</label>
				<input name="fullName" type="text" required value="<?php echo $fullName; ?>">*<br>
				<label for="email">Email:</label>
				<input disabled value="<?php echo $email;?>" name="email" type="email" required>*<br>
				<label for="security">Security:</label>
				<select required name="security">
					<?php echo $securityOptions;?>
				</select>*<br>
				<label for="pass">Password</label>
				<input id="pass" name="pass" placeholder="Unchanged" onclick="changePassword();" minLength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}" title="minimum 8 characther, Must contain at least 1 Lowercase, 1 Uppercase & 1 special character"><br>
				<div class="cancelButton" onClick="window.location.href='config.php';">Cancel</div><br>
				<input type="submit" name="submit" class="saveButton" value="Save">
			</form>
		</div>
	</body>
</html>
						<?php
						}
						else
						{
							invalidRequest();
						}

					}
				}
				elseif($_GET["remove"] == "users")//remove a user
				{
					error_reporting(E_ALL);
					//test existance
					$allUsers = $dataClient -> getAllDocs();
					$exists = false;
					foreach($allUsers -> rows as $currentRow)
					{
						if($currentRow -> id == urlencode(htmlspecialchars($_GET["id"])))
						{
							$exists = true;
						}
					}
					if($exists)
					{
						$user = $dataClient -> getDoc(urlencode(htmlspecialchars($_GET["id"])));
						$dataClient -> deleteDoc($user);
						?>
<script>
	window.alert('User deleted successfully');
	window.location.href="config.php";
</script>
							<?php
					}
					else
					{
						invalidRequest();
					}
				}
				elseif($_GET["edit"] == "levels")//edit a level
				{
					if($_GET["id"] == "new")
					{
						//get maxXP
						$sort = array();
						$levels = $configClient -> getDoc("levels");
						$formula = $levels -> formula;
						foreach($levels -> levels as $currentLevel)
						{
							$sort[] = $currentLevel -> levelNumber;
						}
						$totalLevels = count($sort);
						$levelNumber = $totalLevels +1;
						$level1XP = $levels -> level1XP;
						if($formula = "exponential")
						{
							$maxXP = pow($levelNumber,2) * $level1XP;
						}
						else
						{
							$maxXP = $levelNumber * $level1XP;
						}
						?>
	<body>
		<h1>Add new level</h1>
		<div class="editContent">
			<form method="post">
				<label for="levelName">Level Name:</label>
				<input name="levelName" type="text" required>*<br>
				<label for="color">Progress Bar Color:</label>
				<input name="color" type="color" value="#FFFFFF">*<br>
				<label for="maxXP">Max XP:</label>
				<input disabled name="maxXP" value="<?php echo $maxXP; ?>"><br>
				<div class="cancelButton" onClick="window.location.href='config.php';">Cancel</div><br>
				<input type="submit" name="submit" class="saveButton" value="Save">
			</form>
		</div>
	</body>
</html>
						<?php
					}
					else
					{
						//validate if level exists
						{
							$exists = false;
							$levels = $configClient -> getDoc("levels");
							//loop through levels
							foreach($levels -> levels as $currentLevel)
							{
								if($currentLevel -> levelName == $_GET["id"])
								{
									$exists = true;
								}
							}
							if($exists)//edit level
							{
								$levelName = $_GET["id"];
								$currentLevel = $levels -> levels -> $levelName;
								$maxXP = $currentLevel -> maxXP;
								$progressColor = $currentLevel -> progressColor;
								?>
	<body>
		<h1>Edit level</h1>
		<div class="editContent">
			<form method="post">
				<label for="levelName">Level Name:</label>
				<input name="levelName" type="text" disabled value="<?php echo $levelName; ?>"><br>
				<label for="color">Progress Bar Color:</label>
				<input name="color" type="color" value="<?php echo $progressColor; ?>"><br>
				<label for="maxXP">Max XP:</label>
				<input disabled name="maxXP" value="<?php echo $maxXP; ?>"><br>
				<div class="cancelButton" onClick="window.location.href='config.php';">Cancel</div><br>
				<input type="submit" name="submit" class="saveButton" value="Save">
			</form>
		</div>
	</body>
</html>
						<?php
							}
							else//error
							{
								invalidRequest();
							}
						}
					}
				}
				elseif($_GET["remove"] == "levels")//remove a level
				{
					//get Highest level number
					$sort = array();
					$levels = $configClient -> getDoc("levels");
					foreach($levels -> levels as $currentLevel)
					{
						$sort[$currentLevel -> levelNumber] = $currentLevel -> levelName;
					}
					$highestLevel = count($sort);
					if($sort[$highestLevel] == $_GET["id"])
					{
						$levelName = $_GET["id"];
						unset($levels -> levels -> $levelName);
						$configClient -> storeDoc($levels);
						?>
<script>
	window.alert('Level deleted successfully');
	window.location.href="config.php";
</script>
						<?php
					}
					else
					{
						?>
<script>
	window.alert('You sent an invalid request, Please try again. Should the problem persist Please log an issue at https://github.com/matatacmca/chromegamification');
	window.location.href="config.php";
</script>
						<?php
					}
				}
				elseif($_GET["edit"] == "badges")//edit a badge
				{
					if($_GET["id"] == "new")
					{
						?>
	<body>
		<h1>Add new badge</h1>
		<div class="editContent">
			<form method="post" enctype="multipart/form-data">
				<label for="badgeName">Badge Name:</label>
				<input name="badgeName" type="text" required>*<br>
				<label for="filetoUpload">Badge Image:</label>
    			<input title="Only .png, .jpg and .jpeg files are allowed" type="file" accept=".png,.jpg,.jpeg" name="fileToUpload" id="fileToUpload" required>*<br>
    			<label for="awardedAt"> Award at XP:</label>
    			<input type="number" step="1" name="awardedAt" required>*<br>
    			<label for="badgeDescription">Badge Description:<br></label>
    			<textarea rows="4" cols="50" name="badgeDescription" required></textarea>*<br>
				<div class="cancelButton" onClick="window.location.href='config.php';">Cancel</div><br>
				<input type="submit" name="submit" class="saveButton" value="Save">
			</form>
		</div>
	</body>
</html>
						<?php
					}
					else
					{
						//check existence
						{
							$badges = $configClient -> getDoc("badges");
							$exists = false;
							//loop through badges
							foreach($badges -> badges as $currentBadge)
							{
								if(str_replace(" ","",$currentBadge -> badgeName) == $_GET["id"])
								{
									$exists = true;
								}
							}
							if($exists)
							{
								$badgeID = $_GET["id"];
								$currentBadge = $badges -> badges -> $badgeID;
								?>
	<body>
		<h1>Edit Badge</h1>
		<div class="editContent">
			<form method="post" enctype="multipart/form-data">
				<label for="badgeName">Badge Name:</label>
				<input name="badgeName" type="text" disabled value="<?php echo $currentBadge -> badgeName; ?>">*<br>
				<label for="changeImage">Change Image</label>
				<input id="changeImage" name="changeImage" type="checkbox" onclick="changeBadgeImage();"><br>
				<label for="filetoUpload">Badge Image:</label>
    			<input id="fileToUpload" title="Only .png, .jpg and .jpeg files are allowed" type="file" accept=".png,.jpg,.jpeg" name="fileToUpload" id="fileToUpload" required disabled>*<br>
    			<label for="awardedAt"> Award at XP:</label>
    			<input type="number" step="1" name="awardedAt" required value="<?php echo $currentBadge -> awardedAt; ?>">*<br>
    			<label for="badgeDescription">Badge Description:<br></label>
    			<textarea rows="4" cols="50" name="badgeDescription" required><?php echo $currentBadge -> description; ?></textarea>*<br>
				<div class="cancelButton" onClick="window.location.href='config.php';">Cancel</div><br>
				<input type="submit" name="submit" class="saveButton" value="Save">
			</form>
		</div>
	</body>
</html>
								<?php
							}
							else//error
							{
								invalidRequest();
							}
						}
					}
				}
				elseif($_GET['remove'] == "badges")//remove a badge
				{
					$badgeID = $_GET["id"];
					$badges = $configClient -> getDoc("badges");
					if(!is_null($badges -> badges -> $badgeID))//badge does exist
					{
						unlink("game/badges/" . ($badges -> badges -> $badgeID -> badgeImage));//delete badge from file system
						unset($badges -> badges -> $badgeID);
						$configClient -> storeDoc($badges);
						?>
<script>
	window.alert('Badge removed successfully');
	window.location.href="config.php";
</script>
						<?php
					}
					else//badge does not exist, display error
					{
						invalidRequest();
					}
				}
				elseif($_GET["edit"] == "activities")
				{
					if($_GET["id"] == "positive" || $_GET["id"] == "negative")
					{
						$activity = $configClient -> getDoc("activity");
						$activityType = $_GET["id"];
						$activity = $activity -> $activityType;
						$URLIndex = 0;
						?>
	<body>
		<h1>Edit <?php echo $_GET["id"]; ?> activity scoring</h1>
		<p>
			<strong>Please Note:</strong><br>
			For negative sites you need to specify a negative score.<br>
			You need to add all additional match patterns before you make changes, else it will reset the changes you have made
		</p>
		<div class="editContent">
			<form method="post" enctype="multipart/form-data">
				<div>
				<?php
					foreach($activity as $match)
					{
						?>
					<div class="block">
						<h2><?php echo $match -> name; ?></h2>
						<label for="<?php echo $URLIndex; ?>[enabled]">Enable?</label>
						<input type="checkbox" name="<?php echo $URLIndex; ?>[enabled]" checked><br>
						<label for="<?php echo $URLIndex; ?>[match]">URL contains:</label>
						<input type="text" name="<?php echo $URLIndex; ?>[match]" value="<?php echo $match -> name; ?>"><br>
						<label for="<?php echo $URLIndex; ?>[browse]">Points for browsing:</label>
						<input required title="intervals of 0.01" type="number" step="0.01" name="<?php echo $URLIndex; ?>[browse]" value="<?php echo $match -> browse; ?>">*<br>
						<label for="<?php echo $URLIndex; ?>[click]">Points for clicking:</label>
						<input required title="intervals of 0.01" type="number" step="0.01" name="<?php echo $URLIndex; ?>[click]" value="<?php echo $match -> click; ?>">*<br>
						<label for="<?php echo $URLIndex; ?>[typing]">Points for typing:</label>
						<input required title="intervals of 0.01" type="number" step="0.01" name="<?php echo $URLIndex; ?>[typing]" value="<?php echo $match -> typing; ?>">*<br>
						<label for="<?php echo $URLIndex; ?>[scroll]">Points for scrolling:</label>
						<input required title="intervals of 0.01" type="number" step="0.01" name="<?php echo $URLIndex; ?>[scroll]" value="<?php echo $match -> scroll; ?>">*<br>
					</div>
						<?php
						$URLIndex +=1;
					}
				?>
				</div>
				<div style="display:inline-block;" class="addButton" onClick="addMatchPattern();">Add New Match Pattern</div><br>
				<div class="cancelButton" onClick="window.location.href='config.php';">Cancel</div><br>
				<input type="submit" name="submit" class="saveButton" value="Save">
			</form>
		</div>
	</body>
</html>
						<?php
					}
					else
					{
						invalidRequest();
					}
				}
				else// display error for invalid input
				{
					invalidRequest();
				}
				error_reporting(E_ALL);
			}
		}
	}
	else
	{
		$installURL = "";
		//ssl Verification
		if($_SERVER["HTTPS"] == "on")
		{
			$installURL .= "https://";
		}
		else
		{
			$installURL .= "http://";
		}
		$installURL .= $_SERVER["SSL_TLS_SNI"];
		$installURL .= $_SERVER["REQUEST_URI"];
		echo "<h1>ERROR: The server-side installation has not been completed successfully!</h1>";
		echo "click <a style=\"font-size:40px;\" target=\"_blank\" href=\"$installURL/../install.php\">Here</a> to complete the installation";
		die();
	}	