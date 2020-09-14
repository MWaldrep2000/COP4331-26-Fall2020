<?php
	$inData = getRequestInfo();
	
	$firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $phoneNumber = $inData["phoneNumber"];
    $address = $inData["address"];
    $email = $inData["email"];
	$contactId = $inData["contactId"];

	$conn = new mysqli("localhost:3306", "mwaldrep_spade", "Seagull123$", "mwaldrep_contactyContacts");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
        // No single quotes around userID because stored as int
        $sql = "UPDATE Info SET FirstName = '" . $firstName . "',LastName = '" . $lastName . "',PhoneNumber = '" . $phoneNumber . "',Address = '" .
         $address . "',Email ='" . $email . "' WHERE ID=" . $contactId;

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