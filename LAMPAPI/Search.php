<?php

    // CURRENT QUESTIONS
    // How exactly did the original SearchColors.php file function?
    // What does the blocked off code do?
    // How do we know if the website is doing a get or a post request?
    // How are we formatting the output from the search query?

	$inData = getRequestInfo();
	
	$searchResults = "";
	$searchCount = 0;

    // Correct connection
	$conn = new mysqli("localhost:3306", "mwaldrep_spade", "Seagull123$", "mwaldrep_contactyContacts");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
    } 
    

	else
	{
        $sql = "select Name from Colors where Name like '%" . $inData["search"] . "%' and UserID=" . $inData["userId"];
        
        // This runs the query and returns data into the $result object
        $result = $conn->query($sql);
        
        /////////////////////////////////////////////////////////////////////////////////////////////////
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				if( $searchCount > 0 )
				{
					$searchResults .= ",";
                }
                
				$searchCount++;
				$searchResults .= '"' . $row["Name"] . '"';
			}
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////

		else
		{
			returnWithError( "No Records Found" );
        }
        
		$conn->close();
	}

	returnWithInfo( $searchResults );

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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>