<?php
	error_reporting(!E_WARNING);
	//determine whether app has been installed
	if(is_file("installed.txt"))
	{
		//declare dependancies
		{
			session_start();
			include "dependancies/custom.php";//most variavles are initialized in this script in order to keep this file cleaner
		}
		if(is_null($_SESSION["chromeGamification_user"]))
		{
			echo"ERROR: noUser";
		}
		else //user is signed in
		{
			$now = time();
			$user = $_SESSION["chromeGamification_user"];
			$userData = $dataClient -> getDoc($user);
			$activity = $configClient -> getDoc("activity");
			$badges = $configClient -> getDoc("badges");
			$levels = $configClient -> getDoc("levels");
			$scores = $userData -> scores;
			$match = false;
			//get score for browsing this page
			{
				//loop through positve activity to see if there is a match
				foreach($activity -> positive as $currentPositiveActivity)
				{
					$points = 0;
					if(strpos(strtolower($_POST["URL"]),strtolower($currentPositiveActivity -> name)) !== false)//a match exists
					{
						if($_POST["request"] == "logPage")//score for browsing
						{
							$points = $currentPositiveActivity -> browse;
							$match = true;
						}
						elseif($_POST["request"] == "logClick")
						{
							$points = $currentPositiveActivity -> click;
							$match = true;
						}
						elseif($_POST["request"] == "logTyping")
						{
							$points = $currentPositiveActivity -> typing;
							$match = true;
						}
						elseif($_POST["request"] == "logScrolling")
						{
							$points = $currentPositiveActivity -> scroll;
							$match = true;
						}
					}
					$userData -> scores -> positive = $userData -> scores -> positive + $points;
					$userData -> hits -> positive += 1;
					
				}
				//loop through negative activity to see if there is a match
				foreach($activity -> negative as $currentNegativeActivity)
				{
					$points = 0;
					if(strpos(strtolower($_POST["URL"]),strtolower($currentNegativeActivity -> name)) !== false)//a match exists
					{
						if($_POST["request"] == "logPage")//score for browsing
						{
							$points = $currentNegativeActivity -> browse;
							$match = true;
						}
						elseif($_POST["request"] == "logClick")
						{
							$points = $currentNegativeActivity -> click;
							$match = true;
						}
						elseif($_POST["request"] == "logTyping")
						{
							$points = $currentNegativeActivity -> typing;
							$match = true;
						}
						elseif($_POST["request"] == "logScrolling")
						{
							$points = $currentNegativeActivity -> scroll;
							$match = true;
						}
					}
					$userData -> scores -> negative = $userData -> scores -> negative + $points;
					$userData -> hits -> negative += 1;
				}
				//there was no match, treat activity as Neutral
				if(!$match)
				{
					$userData -> hits -> neutral += 1;
				}
			}
			//add page URL to log
			{
				$userData -> logs -> $now -> timestamp = $now;
				$userData -> logs -> $now -> URL = $_POST["URL"];
				//determine activity type
				if($_POST["request"] == "logPage")//browsing
				{
					$userData -> logs -> $now -> activity = "Browsing";
				}
				elseif($_POST["request"] == "logClick")//clicking
				{
					$userData -> logs -> $now -> activity = "Clicking";
				}
				elseif($_POST["request"] == "logTyping")//typing
				{
					$userData -> logs -> $now -> activity = "Typing";
				}
				elseif($_POST["request"] == "logScrolling")//Scrolling
				{
					$userData -> logs -> $now -> activity = "Scrolling";
				}
			}
			//test if badge should be awarded
			{
				$award = array();
				//loop through badges and see if they are to be awarded
				foreach($badges -> badges as $currentBadge)
				{
					if($currentBadge -> awardedAt <= ($userData -> scores -> negative + $userData -> scores -> positive))
					{
						$badgeName = $currentBadge -> badgeName;
						$badgeID = str_replace(" ","",$badgeName);
						//validate if user already has this badge
						if(is_null($userData -> badges -> $badgeID))//badge must be awarded
						{
							$award[] = $badgeID;
						}
					}
				}
				//display notification& test for awarding
				if(count($award) != 0)//badges need to be awarded
				{
					//display notification
					echo"ALERT: badgeAwarded";
					//loop through badges to award
					foreach($award as $awardBadgeID)
					{
						$userData -> badges -> $awardBadgeID -> awarded = $now;
						$userData -> badges -> $awardBadgeID -> badgeID = $awardBadgeID;
						//add awarded attribute to badge so it cannot be deleted in config
						$badges -> badges -> $awardBadgeID -> awarded = true;
					}
				}
			}
			//test if level up occurred
			{
				$notify = array();
				//loop throgh levels and see if they have been acheived
				foreach($levels -> levels as $currentLevel)
				{
					if(($userData -> scores -> negative + $userData -> scores -> positive) >= $currentLevel -> maxXP)
					{
						$levelName = $currentLevel -> levelName;
						if(is_null($userData -> notifications -> $levelName))
						{
							$notify[] = $levelName;
						}
					}
				}
				if(count($notify) != 0)
				{
					//see if badges were also awarded
					if(count($award != 0))//include notification for badges
					{
						echo ";";
					}
					echo "ALERT: levelUP";//return handler used by content.js inside gamification extension
					//loop through levels to add notification 
					foreach($notify as $notifiedLevel)
					{
						$userData -> notifications -> $notifiedLevel = true;
					}
				}
			}
			//save user Data & badge data
			{
				$dataClient -> storeDoc($userData);
				$configClient -> storeDoc($badges);
			}
			
		}
	}
	else
	{
		die("ERROR: noInstall");
	}
?>