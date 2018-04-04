<?php
	function encrypt($string)
	{
		$hash = $passwordHash;//$passwordHash is auto generated by the install.php
		//encrypt the hash
		{
			$length = strlen($hash);
			$letters = str_split($hash);
			$return = "";
			$encryptCount = 0;
			//repeat the crypt as manny times as the length of the letters
			for($x = 1; $x <= $length; $x++)
			{
				//loop through letters
				foreach($letters as $currentLetter)
				{
					if($encryptCount %3 == 0)
					{
						$return .= chr(ord($currentLetter)+(pow($length,$length) * $x));
					}
					elseif($encryptCount %3 == 1)
					{
						$return .= chr(ord($currentLetter)-($length * $x));
					}
					elseif($encryptCount %3 ==2)
					{
						$return .= bin2hex($currentLetter);
					}
					$encryptCount = $encryptCount +1;
				}
			}
			$hash = strlen(bin2hex($return));
		}
		//encrypt the password
		{
			$crypt = $string;
			$length = strlen($crypt);
			$letters = str_split($crypt);
			$return = "";
			$encryptCount = 0;
			//repeat the crypt as manny times as the length of the letter
			for($x = 1; $x <= $length; $x++)
			{
				//loop through letters
				foreach($letters as $currentLetter)
				{
					if($encryptCount %3 == 0)
					{
						$return .= chr(ord($currentLetter)+(pow($length,$length) * $x) - $hash);
					}
					elseif($encryptCount %3 == 1)
					{
						$return .= chr(ord($currentLetter)-($length * $x) + $hash);
					}
					elseif($encryptCount %3 ==2)
					{
						$return .= bin2hex("$currentLetter $hash");
					}
					
					$encryptCount = $encryptCount +1;
				}
			}
		}
		return(bin2hex($return));
	}
?>