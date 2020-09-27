<?php
	$inData = getRequestInfo();

	$conn = new mysqli("localhost:3306", "mwaldrep_spade", "Seagull123$", "mwaldrep_contactyContacts");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 

	else
	{
        // No single quotes around userID because stored as int
        $sql = "DELETE FROM Info WHERE ID=" . $inData["contactId"];

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