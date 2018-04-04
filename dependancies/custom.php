<?php
	use PHPOnCouch\CouchClient;//php couch DB library as found at https://github.com/dready92/PHP-on-Couch
	include "CouchLink/vendor/autoload.php";//needed to load phponcouch
	$couchDsn = "http://127.0.0.1:5984";//coucgh db settings, if neede these must be manually changed as per documentation provided at https://github.com/dready92/PHP-on-Couch
	$dataDB = "chromegamification_data";//do not change
	$configDB = "chromegamification_config"; // do not change
	$dataClient = new CouchClient($couchDsn,$dataDB);//client used for retrieving user data
	$configClient = new CouchClient($couchDsn,$configDB);//client used for retrieving configuration options
	date_default_timezone_set(strtoupper(date_default_timezone_get()));//sets the timezone to the server timezone
	
	function output($string)//output buffer, used to display status during installation
	{
		$output =  ("<div>[" . date("H:i:s") . " MEM:" . (memory_get_usage()/1024/1024) . "mb] -> $string<br></div>");
		$output = (str_replace("\"","\\\"",$output));
		echo "<script>outputBuffer(\"$output\")</script>";
		ob_flush(); # http://php.net/ob_flush
		flush(); # http://php.net/flush
	}
	function store($print)//debugging fuction, creates a text file of the variable called
	{
	    error_reporting(!E_NOTICE);
	    fopen('print.txt', 'w');
	    file_put_contents('print.txt',print_r($print, true));
	    error_reporting(E_ALL);
	}
	function invalidRequest()//displays invalid requests in config.php
	{
		?>
<script>
	window.alert('You sent an invalid request, Please try again. Should the problem persist Please log an issue at https://github.com/matatacmca/chromegamification');
	window.location.href="config.php";
</script>
		<?php
	}
?>