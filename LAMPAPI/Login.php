<?php

	// All variables in php have $ (dollar signs) in front of them
	// All objects in php have $ (dollar signs) in front of them


	$inData = getRequestInfo();
	
	// Creating variables for use later
	$id = 0;
	$firstName = "";
	$lastName = "";

	// Creates connection between php and database
	$conn = new mysqli("localhost", "mwaldrep_mwaldrep", "Seagull123$", "mwaldrep_contactyContacts");

	// Checking the connect_error variable in the $conn object
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 

	else
	{
		// dots below mean string concatenation, SELECT FROM are keywords in sql
		// This code is creating the query request to get data from the database
		$sql = "SELECT ID,FirstName,LastName FROM Users where Login='" . $inData["login"] . "' and Password='" . $inData["password"] . "'";

		// Performs query on sql database, this uses the function query() from the $conn object
		$result = $conn->query($sql);

		// If there is data
		if ($result->num_rows > 0)
		{
			// Gets the information from the result object using the fetch_assoc() function in the
			// result object and stores it in the row object
			$row = $result->fetch_assoc();
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];
			$id = $row["ID"];
			
			// returns the following objects as a json file
			returnWithInfo($firstName, $lastName, $id );
		}

		// If there is no data
		else
		{
			returnWithError( "No Records Found" );
		}

		// Closes the connection to the database
		$conn->close();
	}
	
	// Takes in json object and outputs a php object
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');

		// Prints out the json object
		echo $obj;
	}
	
	// Creates and returns a json object with values that show an error
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>