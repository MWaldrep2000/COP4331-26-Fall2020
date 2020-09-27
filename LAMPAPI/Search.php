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
		$searchFlag = $inData["firstLastFlag"];

		// First Name
		if ($searchFlag == 0)
		{
			$sql = "select FirstName,LastName,PhoneNumber,Address,Email,ID from Info where FirstName like '%" .
			 $inData["search"] . "%' and UserID=" . $inData["userId"];
		}

		// Last Name
		else
		{
			$sql = "select FirstName,LastName,PhoneNumber,Address,Email,ID from Info where LastName like '%" .
			$inData["search"] . "%' and UserID=" . $inData["userId"];
		}

		$result = $conn->query($sql);

		$counter = 0;

		if ($result->num_rows > 0)
		{
			// Controls each contact
			while($row = $result->fetch_assoc())
			{
				if( $searchCount > 0 )
				{
					$searchResults .= ",";
				}
				
				$searchCount++;
				//$searchResults .= '"' . $row["FirstName"] . '"' . ;
				for ($i = 0; $i < 6; $i++)
				{
					$infoLoop = $counter % 6;
					switch ($i)
					{
						case 0:
							$searchResults .= '{"FirstName":"' . $row["FirstName"] . '",';
							break;

						case 1:
							$searchResults .= '"LastName":"' . $row["LastName"] . '",'; 
							break;

						case 2:
							$searchResults .= '"PhoneNumber":"' . $row["PhoneNumber"] . '",';
							break;

						case 3:
							$searchResults .= '"Address":"' . $row["Address"] . '",'; 
							break;

						case 4:
							$searchResults .= '"Email":"' . $row["Email"] . '",'; 
							break;
						
						case 5:
							$searchResults .= '"ContactID":' . $row["ID"] . '}'; 
							break;

						default:
							break;
					}
					$counter++;
				}
				
			}

			returnWithInfo( $searchResults );
		}
		
		else
		{
			returnWithError( "No Records Found" );
		}
		
		
		$conn->close();
	}
	


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
	
	// {"FirstName" : "Sasuke",                        0 % 6 = 0
	// "LastName" : "Uchiha",                          1 % 6 = 1
	// "PhoneNumber" : "",                             2
	// "Address" : "Hidden Leaf Village",              3
	// "Email" : "UchihaSasuke@Orochimaru.net",
	// "ContactID" : 43},                              5 % 6 = 5
	// {"FirstName" : "Naruto",                        6 % 6 = 0
	// "LastName" : "Uzumaki",                         7 % 6 = 1
	// "PhoneNumber" : "555",
	// "Address" : "Hidden Leaf Village",
	// "Email" : "hokage@Konoha.net",
	// "ContactID" : 44}
?>