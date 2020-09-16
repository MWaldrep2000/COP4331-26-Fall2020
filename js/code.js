var urlBase = 'http://COP4331-26.com/LAMPAPI';
var extension = 'php';

var userId = 0;
var firstName = "";
var lastName = "";
var Address = "";
var PhoneNumber = "";
var Email = "";
var ContactID = "";

// ******** DELETE LATER ******************
function FakeLogin()
{
	var narutoNames = ["Naruto Uzumaki", 
						"Sasuke Uchiha", 
						"Sakura Haruno", 
						"Rock Lee", 
						"Neji Hyuga", 
						"Tenten IDK", 
						"Hinata Hyuga", 
						"Shino Aburame", 
						"Kiba Inuzuka", 
						"Ino Yamanaka", 
						"Shikamaru Nara", 
						"Choji Akamichi"
						];

	var select = document.getElementById("select");

	for(var i = 0; i < narutoNames.length; i++){
		var name = narutoNames[i];
		var element = document.createElement("option");
		element.value = name;
		element.textContent = name;
		select.appendChild(element);
	}
					
}
// *********************************************************

function AddContact()
{
    firstName = document.getElementById("firstName").value;
    lastName = document.getElementById("lastName").value;
    PhoneNumber= document.getElementById("PhoneNumber").value;
    Email = document.getElementById("Email").value;
    Address = document.getElementById("Address").value;
    
    document.getElementbyId("ContactAddRessult").innerHTML = "";
    
    var myObject = { firstName: firstName, lastName: lastName, PhoneNumber: PhoneNumber, Email: Email, Address: Address};
    var jsonPayload = JSON.stringify(myObject);
    alert(jsonPayload);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    
    try
    {
        xhr.onreadstatechange = function()
        {
            if(this.readySate == 4 && this.status == 200)
            {
                document.getElementById("ContactAddResult").innerHTML = "Contact has been added";
            }
        };
        xhr.send(jsonPayload);
    }
    catch(err)
    {
        document.getElementbyId("ContactAddResult").innerHTML = err.message;
    }
}

function Login()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	var base_url = window.location.origin;
	
	var login = document.getElementById("username").value;

	var password = document.getElementById("password").value;
	var hash = md5( password );

//	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';
	
	var myObject = { login: login, password : password}; //this maybe slightly easier
	var jsonPayload = JSON.stringify(myObject);     //this maybe slightly easier
	
	alert(jsonPayload)
	
	var url = urlBase + '/Login.' + extension;
	alert(url);
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false); //opening connection telling its gonna be a post, false means synchronous means less code
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8"); //telling it im talking in json
	try
	{
		xhr.send(jsonPayload);
		alert(jsonPayload);
		
		var jsonObject = JSON.parse( xhr.responseText );
		//or jsonObject["id"]
		
		userId = jsonObject.id;
		alert(userId);
		if( userId < 1 )
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;
		alert(firstName);
		alert(lastName);
		document.getElementById("loginResult").innerHTML = "Login success";
	
		window.location.href = "userpage.html"; //edit for next page
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}

// WHEN THEY LOGIN WE NEED TO HAVE SOMETHING THAT GETS ALL OF THE CONTACTS
}

function Signup()
{
// gets all the values from the text boxes
    userId = 0;
	firstName = "";
	lastName = "";
    
    firstName = document.getElementById("firstName").value;
    lastName = document.getElementById("lastName").value;
    
    var login = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var confirmpassword = document.getElementById("confirmpassword").value;
    
// checks if the passwords are the same    
    if(password != confirmpassword)
    {
        document.getElementById("RegisterResult").innerHTML = "Passwords do not match";
        return;
    }

// create an object that contains all of the values from
// the user inputs. then converts them to a json payload
    var myObject = { firstName: firstName, lastName:lastName, login: login, password : password}; //this maybe slightly easier
    var jsonPayload = JSON.stringify(myObject);
    
    var url = urlBase + '/Register.' + extension;
    
    var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false); //opening connection telling its gonna be a post, false means synchronous means less code
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8"); //telling it im talking in json
	
	try
	{
		xhr.send(jsonPayload);
		alert(jsonPayload);
		
		var jsonObject = JSON.parse( xhr.responseText );
		//or jsonObject["id"]
		
		userId = jsonObject.id;
		alert(userId);
		if( userId < 1 )
		{
			document.getElementById("RegisterResult").innerHTML = "Username has already been taken";
			return;
		}
		document.getElementById("RegisterResult").innerHTML = "Registration successful";

		//saveCookie();             //decide if we wanna save cookies
	
		window.location.href = "index.html"; //edit for next page
	}
	catch(err)
	{
		document.getElementById("RegisterResult").innerHTML = err.message;
	}

}
