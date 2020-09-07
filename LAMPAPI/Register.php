<?php

// All variables in php have $ (dollar signs) in front of them
// All objects in php have $ (dollar signs) in front of them


$inData = getRequestInfo();

// Creating variables for use later
$id = 0;

// Creates connection between php and database
$conn = new mysqli("localhost:3306", "mwaldrep_spade", "Seagull123$", "mwaldrep_contactyContacts");

// Checking the connect_error variable in the $conn object
if ($conn->connect_error)
{
  returnWithError( $conn->connect_error );
}

else
{
  // dots below mean string concatenation, SELECT FROM are keywords in sql
  // This code is creating the query request to get data from the database
  $sql = "SELECT ID FROM Users where Login='" . $inData["login"] . "'";

  // Performs query on sql database, this uses the function query() from the $conn object
  $result = $conn->query($sql);

  // Verify username uniqueness
  if ($result->num_rows > 0)
  {
    returnWithError("Username is taken.");
  }
  else
  {
    // register the new user
    // sql line we want
    // INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
    $sql = "INSERT INTO Users (FirstName,LastName,Login,Password) VALUES ('" . $inData["firstName"] . "','" . $inData["lastName"] . "','" . $inData["login"] . "','" . $inData["password"] . "')";

    // Query made, and confirmation received.
    $confirmation = $conn->query($sql);


    //receive the newly made ID from the DB
    $sql = "SELECT ID FROM Users where Login='" . $inData["login"] . "'";
    $returnToFE = $conn->query($sql);
    $row = $returnToFE->fetch_assoc();
    $id = $row["ID"];

    // tell front end user was created
    returnWithInfo($id);
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

function returnWithInfo( $id )
{
  $retValue = '{"id":' . $id . '}';
  sendResultInfoAsJson( $retValue );
}

?>
