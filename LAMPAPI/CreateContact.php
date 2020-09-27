<?php
	$inData = getRequestInfo();
	
	$firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $phoneNumber = $inData["phoneNumber"];
    $address = $inData["address"];
    $email = $inData["email"];
	$userId = $inData["userId"];

	$conn = new mysqli("localhost:3306", "mwaldrep_spade", "Seagull123$", "mwaldrep_contactyContacts");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 

	else
	{
        // No single quotes around userID because stored as int
        $sql = "insert into Info (FirstName,LastName,PhoneNumber,Address,Email,UserID) VALUES ('" . 
        $firstName . "','" . $lastName . "','" . $phoneNumber . "','". $address . "','" . $email . "'," . $userId . ")";

		if( $result = $conn->query($sql) != TRUE )
		{
			returnWithError( $conn->error );
        }
        
		$conn->close();
	}
	
	returnWithError("");
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
?>