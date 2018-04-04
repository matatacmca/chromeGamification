<?php
	//determine whether app has been installed
	if(is_file("installed.txt"))
	{
		//declare dependancies
		{
			error_reporting(!E_WARNING);
			include "dependancies/custom.php";//most variables are initialized in this script in order to keep this file cleaner
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
			error_reporting(E_ALL);
		}
		?>
		<?php
		error_reporting(!E_WARNING);
		if((is_null($_SESSION["chromeGamification_user"])))//validates if user has signed in
		{
			echo"<script>window.alert(\"You are not signed in. use the popup to sign in, and try again \");</script>";
		}
		else//user is signed in
		{
			//validte user access
			{
				$userData = $dataClient -> getDoc($_SESSION["chromeGamification_user"]);
				if(($userData -> security == "Administrator") || ($userData -> security == "Manager"))//user is not admin
				{
					$datePicker = date("Y-m-d");
					//which page to display
					if(count($_POST) > 0)//form was submited, generate a report
					{
						if($_POST["ChartQuery"] == "scoreGraph" && $_POST["secret"] == "giveMeAllTheAnswers")
						{
echo "hello";
						}
						if($_POST["reportType"] == "Score Graph")
						{
//var_dump($_POST);
							?>
<!DOCTYPE html>
<html>
	<head>
		<title>User Score Chart || chromeGamification</title>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	  <script type="text/javascript">
google.charts.load("current", {packages:['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
var data = google.visualization.arrayToDataTable([
	["Element", "Density","negative", { role: "style" } ],
	["Copper", 8.94,-57, "#b87333"],
	["Silver", 10.49,0, "silver"],
	["Gold", 19.30,0, "gold"],
	["Platinum", 21.45,0, "color: #e5e4e2"]
]);

var view = new google.visualization.DataView(data);
view.setColumns([0, 1,
           { calc: "stringify",
             sourceColumn: 1,
             type: "string",
             role: "annotation" },
           2]);

var options = {
title: "Density of Precious Metals, in g/cm^3",
width: 600,
height: 400,
bar: {groupWidth: "95%"},
legend: { position: "none" },
};
var chart = new google.visualization.ColumnChart(document.getElementById("chart"));
chart.draw(view, options);
}
		</script>
		<style>
body
{
	width:100vw;
	max-width:100vw;;
	height:100vh;
	max-height:100vh;
	margin:0 auto;
	padding:0;
}
div
{
	width:100%;
	height:100;
	vertical-align:middle;
	margin:0 auto;
}
		</style>
	</head>
	<body>
		<div id="chart" style="width: 900px; height: 300px;"></div>
	</body>
</html>
							<?php
						}
						else
						{
							invalidRequest();
						}
					}
					else // no form submitted, use wizard
					{
						$allusers = $dataClient -> getAllDocs();
						?>
<!DOCTYPE html>
<html>
	<head>
		<title>Report Generation Wizzard || chromeGamification</title>
		<style>
.reportSpecificContent
{
	display:none;
}
		</style>
	</head>
	<body>
		<h1>Report Generation Wizzard</h1>
		<p>Complete the form to create your report</p>
		<form method="post">
			<label for="reportType">Report Type:<br></label>
			<select onchange="reportQuestions();" name="reportType" id="reportType">;
				<option title="Displays a comparitive graph of users' scores, as per your selection">Score Graph</option>
				<option title="Displays a comparitive graph of how many hits a user has on specified sites as per your specifications">Hit Graph</option>
				<option title="Displays a log of a specific users data in a certain period of time">Activity Log</option>
				<option title="Displays a comparitive graph categorising user data into positive, neutral and negative activity, as per your selection">Activity Type Graph</option>
			</select>
			<div id="Score Graph" class="reportSpecificContent">
				<h3>Select users to display in the report</h3>
				<?php
					//loop through rows
					foreach($allusers -> rows as $currentRow)
					{
						$userID = $currentRow -> id;
						//get user info
						$currentUser = $dataClient -> getDoc($userID);
						$fullName = $currentUser -> details -> fullName;
						echo "<input type=\"checkbox\" disabled name=\"users[]\" value=\"$userID\">$fullName<br>";
					}
				?>
			</div>
			<div id="Hit Graph" class="reportSpecificContent">
				<h3>Select users to display in the report</h3>
				<?php
					//loop through rows
					foreach($allusers -> rows as $currentRow)
					{
						$userID = $currentRow -> id;
						//get user info
						$currentUser = $dataClient -> getDoc($userID);
						$fullName = $currentUser -> details -> fullName;
						echo "<input type=\"checkbox\" disabled name=\"users[]\" value=\"$userID\">$fullName<br>";
					}
				?>
				<label for="hits">Sites to test (comma separated, case insensitive)<br></label>
				<textarea cols="50" rows="5" required disabled name="hits" placeholder="FacEbook.com,mail.google.com,github,"></textarea><br>
			</div>
			<div id="Activity Log" class="reportSpecificContent">
				<h3>Select a user:</h3>
				<select name="user">
					<?php
						foreach($allusers -> rows as $currentRow)
					{
						$userID = $currentRow -> id;
						//get user info
						$currentUser = $dataClient -> getDoc($userID);
						$fullName = $currentUser -> details -> fullName;
						echo "<option value=\"$userID\">$fullName</option>";
					}
					?>
				</select><br>
				<label for="startDate">Start Date (included):<br></label>
				<input onchange="dateRange();" id="startDate" name="startDate" type="date" required max="<?php echo $datePicker;?>" value="<?php echo $datePicker;?>"><br>
				<label for="endDate">End Date (included):<br></label>
				<input title="Day cannot be befor the start date" id="endDate" name="endDate" type="date" required max="<?php echo $datePicker;?>" value="<?php echo $datePicker;?>"><br>
				
			</div>
			<div id="Activity Type Graph" class="reportSpecificContent">
				<h3>Select users to display in the report</h3>
				<?php
					//loop through rows
					foreach($allusers -> rows as $currentRow)
					{
						$userID = $currentRow -> id;
						//get user info
						$currentUser = $dataClient -> getDoc($userID);
						$fullName = $currentUser -> details -> fullName;
						echo "<input type=\"checkbox\" disabled name=\"users[]\" value=\"$userID\">$fullName<br>";
					}
				?>
			</div>
			<br>
			<br>
			<br>
			<input type="submit" name="submit" value="Generate Report">
		</form>
		<script>
dateRange();
reportQuestions();
function dateRange()
{
	var start = document.getElementById('startDate').value;
	document.getElementById('endDate').min = start;
}
function reportQuestions()
{
	//hide non relevant questions
	{
		var blocks = document.getElementsByTagName('form')[0].children;
		blockNumber = blocks.length;
		for (i=0;i<blockNumber;i++)
		{
			if(blocks[i].tagName == "DIV")
			{
				blocks[i].style.display="none";
				//disable inputs
				{
					var blockChildren = blocks[i].children;
					for(j=0;j<blockChildren.length;j++)
					{
						if(blockChildren[j].tagName == "INPUT" || blockChildren[j].tagName == "TEXTAREA" || blockChildren[j].tagName == "SELECT")
						{
							blockChildren[j].disabled=true;
						}
					}
				}
			}
		}
	}
	//display the relevent questions
	{
		var selectedReport = document.getElementById("reportType").value;
		var questions = document.getElementById(selectedReport);
		questions.style.display = "block";
		//ensure children form input elements are enabled
		{
			var children = questions.children;
			length = children.length;
			for(x=0;x<length;x++)
			{
				if(children[x].tagName == "INPUT" || children[x].tagName == "TEXTAREA" || children[x].tagName == "SELECT")
				{
					children[x].disabled=false;
				}
			}
		}
	}
}
		</script>
	</body>
</html>
						<?php
					}
				}
				else
				{
					echo "<script>window.alert(\"You are not authorised to view this page, Please contact your adminitrator to resolve the issue\");</script>";
				}
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
?>