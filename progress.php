<?php
	//determine whether app has been installed
	if(is_file("installed.txt"))
	{
		//declare dependancies
		{
			include "dependancies/custom.php";//most variavles are initialized in this script in order to keep this file cleaner
			//include "dependancies/encryptor/encrypt.php";
			session_start();
			$pageURL = "";
			//ssl Verification
			if($_SERVER["HTTPS"] == "on")
			{
				$pageURL .= "https://";
			}
			else
			{
				$pageURL .= "http://";
			}
			$pageURL .= $_SERVER["SSL_TLS_SNI"];
			$pageURL .= $_SERVER["REQUEST_URI"];
		}
		error_reporting(!E_WARNING);
		if((is_null($_SESSION["chromeGamification_user"])))//validates if user has signed in
		{
			if(is_null($_POST["submit"]))
			{
				$loginURL = "";
				//ssl Verification
				if($_SERVER["HTTPS"] == "on")
				{
					$loginURL .= "https://";
				}
				else
				{
					$loginURL .= "http://";
				}
				$loginURL .= $_SERVER["SSL_TLS_SNI"];
				$loginURL .= $_SERVER["REQUEST_URI"];
				if(is_null($_SESSION["chromeGamification_loginError"]))
				{
					$_SESSION["chromeGamification_loginError"] = "Please sign in";
				}
				error_reporting(E_ALL);
				?>
<h1><?php echo $_SESSION["chromeGamification_loginError"];?></h1>
<form method="post" target="_blank" action=<?php echo"$loginURL";?>>
	<label for="loginEmail">Email:</label>
	<input title="provide your email address" name="loginEmail" type="email" placeholder="someone@example.com" required>*<br>
	<label for="loginPassword">Password:</label>
	<input title="provide your password" name="loginPassword" type="password" placeholder="password" required>*<br>
	<input name="submit" type="submit" value="Login">
</form>
				<?php
			}
			elseif($_POST["submit"] == "Login")//validate credentials
			{
				$allUsers = $dataClient -> getAllDocs();
				$authorised = false;
				foreach($allUsers -> rows as $currentRow)
				{
					if($currentRow -> id == (urlencode(htmlspecialchars($_POST["loginEmail"]))))
					{
						$user = $dataClient -> getDoc($currentRow -> id);
						if($user -> pass == encrypt($_POST["loginPassword"]))
						{
							unset($_SESSION["chromeGamification_loginError"]);
							$authorised = true;
							$_SESSION["chromeGamification_user"] = urlencode(htmlspecialchars($_POST["loginEmail"]));
				?>
<script>
	window.alert("Sucessfully logged in! Thank You");
	window.close();
</script>
				<?php
						}
					}
				}
				if(!$authorised)
				{
					$_SESSION["chromeGamification_loginError"] = "Incorrect credentials entered, please try again";
				}
			}
		}
		else//user is signed in
		{
			$userData = $dataClient -> getDoc($_SESSION["chromeGamification_user"]);
			$positive = $userData -> scores -> positive;
			$neutral = $userData -> scores -> neutral;
			$negative = $userData -> scores -> negative;
			$score = intval($positive + $negative);
			$highestLevel = 0;
			$lowestLevel = null;
			//calculate Progress bar
			{
				//get levels config
				$levels = $configClient -> getDoc("levels");
				$level1XP = $levels -> level1XP;
				foreach($levels -> levels as $currentLevel)
				{
					//see if score is in this level
					if($score < ($currentLevel -> maxXP))
					{
                        //determine the level that this score fits in to
                        if((is_null($lowestLevel)) || ($lowestLevel >= $currentLevel -> levelNumber))
                        {
                            $lowestLevel = $currentLevel -> levelNumber;
                            $levelName = $currentLevel -> levelName;
                            $progressColor = $currentLevel -> progressColor;
                            $maxXP = $currentLevel -> maxXP;
                            $percentage = $score / $maxXP * 100;
					    }
					}
					else
					{
                        if($highestLevel < $currentLevel -> levelNumber)
                        {
                            $highestLevel = $currentLevel ->levelNumber;
                            $highestLevelName = $currentLevel -> levelName;
                            $highestProgressColor = $currentLevel -> progressColor;
                            $highestMaxXP = $currentLevel -> maxXP;
                        }
					}
				}
				//determine if user has Exceeded the charts
				if((is_null($lowestLevel)))
				{
					$levelName = $highestLevelName;
					$progressColor = $highestProgressColor;
					$maxXP = $highestMaxXP;
					$percentage = 100;
				}
			?>
<div id="progressBar">
	<div id="progress" style="<?php echo "width:$percentage%;background-color:$progressColor;";?>"><?php echo "Your Level: $levelName &emsp; $score / $maxXP";?></div>
</div><div class="spacer"></div>
			<?php
			}
			//display Badges
			{
				?>
<div id="badges">
    <h2>Your Badges</h2>
    <div id="badgeList">
				<?php
				$configBadges = $configClient -> getDoc("badges");
				//create Sorting arrays based on timestamp
				{
					$sort = array();
					$data = array();
				}
				//loop through the badges that have been awarded
				foreach($userData -> badges as $currentBadge)
				{
					//store badges into arrays for sorting
					{
						if(!in_array($currentBadge -> awarded,$sort))
						{
							$sort[] = $currentBadge -> awarded;
						}
						$data[$currentBadge -> awarded][] = $currentBadge -> badgeID;
					}
				}
				rsort($sort);//will sort the awarded date to  the newestbadge first	
				//loop through sorting array
				foreach($sort as $awardedDate)
				{
					foreach($data[$awardedDate] as $currentBadge)
					{
						echo "<div class=\"badge\">";
						$badgeID = $currentBadge;
						$badgeDetails = $configBadges -> badges -> $badgeID;
						
						echo "<img src=\"$pageURL/../game/badges/" . $badgeDetails -> badgeImage . "\">";
						echo "<h3>" . $badgeDetails -> badgeName . "</h3>";
						echo "<span><strong>" . date("j M Y",$awardedDate) . "</strong><br>" . $badgeDetails -> description . "</span></div>";
					}
				}
			?>
	</div>
</div>
			<?php
			}
		}
		error_reporting(E_ALL);
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
?>