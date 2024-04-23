window.onload = function() {
    getdiscrim();
    mobile();
    updatethestuffdms();
    updateTimestamp();
var xhr = new XMLHttpRequest();
xhr.open("GET", "../check.php", true);
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.message === "Cookie matches user in the database") {
        	var discriminator = response.user.discriminator;
        	checkAuthorization(discriminator);
            return;
        } else {
            window.location.href = "../";
        }
    }
};
xhr.send();


fetchData();
fetchUserStatus();
setInterval(fetchData, 60000);
};

function checkAuthorization(discriminator) {
    // Create a new XHR object
    var xhr = new XMLHttpRequest();
    var urlParams = new URLSearchParams(window.location.search);
    var chatId = urlParams.get('chatid');
    
    // Define the URL endpoint to the PHP script
    var url = "permcheck.php?chatid=" + encodeURIComponent(chatId);
    
    // Set the HTTP method to POST
    xhr.open("POST", url, true);
    
    // Set the Content-Type header
    xhr.setRequestHeader("Content-Type", "application/json");
    
    // Define the data to send in the request body (including discriminator)
    var data = JSON.stringify({ "discriminator": discriminator });
    
    // Define callback function for when the request completes
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            // Check if the request was successful
            if (xhr.status === 200) {
                // Check the response text
                if (xhr.responseText === "auth") {
                    console.log("Authorization successful");
                    // Do something when authorization is successful
                } else {
                    console.log("Authorization failed");
                    window.location.href = "/403.html";
                    // Do something when authorization fails
                }
            } else {
                // Request failed, handle error here if needed
                console.error("Request failed with status:", xhr.status);
            }
        }
    };
    
    // Send the request with the data in the body
    xhr.send(data);
}


function mobile(){
	var screenWidth = window.innerWidth;
	var chat = document.getElementById("chatwin");
	var side = document.getElementById("side");
	if (screenWidth < 650) {
		side.style.display = "none";
		chat.style.display = "";
	}
};

function showside(){
	var screenWidth = window.innerWidth;
	var chat = document.getElementById("chatwin");
	var side = document.getElementById("side");
	if (screenWidth < 650) {
		chat.style.display = "none";
		side.style.display = "flex";
	}
};


document.getElementById("deleteButton").addEventListener("click", function() {
// Retrieve the chat name from the input field
var chatName = document.getElementById("text21").value.trim();

// Check if the chat name is not empty
if (chatName !== "") {
    // Create a new XHR object
    var xhr = new XMLHttpRequest();

    // Define the URL endpoint to the PHP script that deletes the chat
    var url = "del.php";

    // Set the HTTP method to POST
    xhr.open("POST", url, true);

    // Set the Content-Type header
    xhr.setRequestHeader("Content-Type", "application/json");

    // Define the data to send in the request body (chat name)
    var data = JSON.stringify({ "chat_name": chatName });

    // Define callback function for when the request completes
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            // Check if the request was successful
            if (xhr.status === 200) {
                // Request was successful, handle response here if needed
                
                location.reload()
                // Refresh the page or update UI as needed
            } else {
                // Request failed, handle error here if needed
                
            }
        }
    };

    // Send the request with the data in the body
    xhr.send(data);
} else {
    // If chat name is empty, display an error message or handle it accordingly
    
}
});



function addm(discriminator, frienddiscriminator, name) {
// Create a new XHR object
var xhr = new XMLHttpRequest();

// Define the URL endpoint to your PHP script
var url = "add.php";

// Set the HTTP method to POST
xhr.open("POST", url, true);

// Set the Content-Type header
xhr.setRequestHeader("Content-Type", "application/json");

// Define the data to send in the request body (including discriminator)
var data = JSON.stringify({ "discriminator": discriminator, "friend": frienddiscriminator, "name": name });

// Define callback function for when the request completes
xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        // Check if the request was successful
        if (xhr.status === 200) {
            // Request was successful, handle response here if needed
            
        } else {
            // Request failed, handle error here if needed
            
        }
    }
};

// Send the request with the data in the body
xhr.send(data);
}

function addChatsToDiv(chats) {
var dmsDiv = document.getElementById("dms");
// Clear previous data in the div
dmsDiv.innerHTML = "";
// Append each chat to the div
chats.forEach(function(chat) {
    var li = document.createElement("li");
    var a = document.createElement("a");
    a.href = "/login/chat/?chatid=" + encodeURIComponent(chat.chat_id); // Encode chat_id
    a.textContent = chat.chat_name; // Use chat_name as the link text
    li.appendChild(a);
    dmsDiv.appendChild(li);
});
}

function updatedm(x) {
// Create a new XHR object
var xhr = new XMLHttpRequest();

// Define the URL endpoint to dm.php
var url = "dm.php";

// Set the HTTP method to POST
xhr.open("POST", url, true);

// Set the Content-Type header
xhr.setRequestHeader("Content-Type", "application/json");

// Define the data to send in the request body (including discriminator)
var data = JSON.stringify({ "discriminator": x });

// Define callback function for when the request completes
xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        // Check if the request was successful
        if (xhr.status === 200) {
            // Request was successful, handle response here if needed
            var response = JSON.parse(xhr.responseText);
            var chats = response.chats;
            addChatsToDiv(chats);
        } else {
            // Request failed, handle error here if needed
            
        }
    }
};

// Send the request with the data in the body
xhr.send(data);
}

function discrimadd() {
    friend = document.getElementById('text18').value;
    name = document.getElementById('text19').value;
var xhr = new XMLHttpRequest();
xhr.open("GET", "../check.php", true);
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.message === "Cookie matches user in the database") {
            var discriminator = response.user.discriminator;
            
            if (discriminator) {
                addm(discriminator, friend, name);
                updatedm(discriminator);
                location.reload()
            } else {
                
            }
        } else {
            
        }
    }
};
xhr.send();
}


function updateScroll(){
var element = document.getElementById("chat");
element.scrollTop = element.scrollHeight;
}

function getdiscrim() {
var xhr = new XMLHttpRequest();
xhr.open("GET", "../check.php", true);
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.message === "Cookie matches user in the database") {
            var discriminator = response.user.discriminator;
            if (discriminator) {
                // Retrieve chat ID from the URL
                var urlParams = new URLSearchParams(window.location.search);
                var chatId = urlParams.get('chatid');

                // Pass discriminator and chat ID to addDiscriminatorToChat function
                addDiscriminatorToChat(chatId, discriminator);
            } else {
                
            }
        } else {
            
        }
    }
};
xhr.send();
}


function addDiscriminatorToChat(chatId, discriminator) {
// Construct the payload
var data = JSON.stringify({
    "discriminator": discriminator
});

// Create a new XMLHttpRequest object
var xhr = new XMLHttpRequest();

// Define the request parameters
var url = "perm.php?chatid=" + chatId;
var method = "POST";

// Configure the request
xhr.open(method, url, true);
xhr.setRequestHeader("Content-Type", "application/json");

// Define the callback function when the request completes
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            
        } else {
            
        }
    }
};

// Send the request with the payload
xhr.send(data);
}


function updatethestuffdms() {
    friend = document.getElementById('text18').value;
var xhr = new XMLHttpRequest();
xhr.open("GET", "../check.php", true);
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.message === "Cookie matches user in the database") {
            var discriminator = response.user.discriminator;
            
            if (discriminator) {
                updatedm(discriminator);
            } else {
                
            }
        } else {
            
        }
    }
};
xhr.send();
}



function fetchData() {
// Create a new XMLHttpRequest object
    var urlParams = new URLSearchParams(window.location.search);
var chatId = urlParams.get('chatid');
if (!/^".*"$/.test(chatId)) {
   if (!chatId.startsWith('"') && !chatId.endsWith('"')) {
       chatId = '"' + chatId + '"';
   }
}

const xhr = new XMLHttpRequest();

// Define the request parameters
const url = 'get.php';
const method = 'GET';

// Configure the request
xhr.open("GET", "get.php?chatid=" + chatId, true);

// Define the callback function when the request completes
xhr.onload = function() {
    if (xhr.status === 200) {
        // Request was successful, handle the response

        const responseData = JSON.parse(xhr.responseText);
        displayChat(responseData);

    } else {
        // Request failed, handle the error
        
    }
};

// Define the callback function for network errors
xhr.onerror = function() {
    
};

// Send the request
xhr.send();
}

function displayChat(data) {
// Get the chat div
const chatDiv = document.getElementById('chat');

// Clear previous content
chatDiv.innerHTML = '';

// Iterate over the data and format it
data.forEach(function(message) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');

    const usernameElement = document.createElement('span');
    usernameElement.classList.add('username');
    usernameElement.textContent = message.username;

    const messageTextElement = document.createElement('span');
    messageTextElement.classList.add('message-text');
    messageTextElement.textContent = " " + message.message;

    const datetimediv = document.createElement('div')

    const dateElement = document.createElement('span');
    const date = message.datetime;
    var [formattedDate, formattedTime] = date.split(/ /);
    dateElement.classList.add('date');
    dateElement.textContent = ' [' + formattedDate + ']';

    const timeElement = document.createElement('span');
    timeElement.classList.add('time')
    timeElement.textContent = ' [' + formattedTime + ']';


    datetimediv.classList.add('datetime');
    datetimediv.textContent = timeElement.textContent + dateElement.textContent;

    const br = document.createElement('br')

    messageElement.appendChild(usernameElement);
    messageElement.appendChild(messageTextElement);
    messageElement.appendChild(datetimediv);



    chatDiv.appendChild(messageElement);
    setTimeout(() => {
      updateScroll();
    }, 400);
});
}

const node = document.getElementById("text17");
node.addEventListener("keyup", function(event) {
if (event.key === "Enter") {
    sendMessage();
    fetchData();
}
});

function sendMessage(event) {

// Fetch username from get.php
var xhr = new XMLHttpRequest();
xhr.open("GET", "../check.php", true);

xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var username = response.user.username; // Extracting username from response

        sendData(username);
    }
};

xhr.send();
}


function sendData(username) {
var message = document.getElementById("text17").value; // Get the message value from the input field
var urlParams = new URLSearchParams(window.location.search);
var chatid = urlParams.get('chatid');
// Construct the payload
var data = JSON.stringify({
    "username": username,
    "message": message
});

var xhr = new XMLHttpRequest();
xhr.open("POST", "msg.php?chatid=" + chatid, true); // Append chatid to the URL
xhr.setRequestHeader("Content-Type", "application/json"); // Set content type to JSON

xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        
        if (response.message_id) {
            
        }
        document.getElementById("text17").value = "";
    }
};

xhr.send(data); // Send the payload
}


function updateTimestamp() {
var xhr = new XMLHttpRequest();
xhr.open("GET", "../check.php", true);
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.message === "Cookie matches user in the database") {
            var discriminator = response.user.discriminator;
            if (discriminator) {
                sendDiscriminator(discriminator);
            } else {
                
            }
        } else {
            
        }
    }
};
xhr.send();
}

function sendDiscriminator(discriminator) {
var xhr = new XMLHttpRequest();
xhr.open("POST", "update.php", true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        
    }
};
xhr.send("discriminator=" + encodeURIComponent(discriminator));
}

setInterval(updateTimestamp, 60000);

function fetchUserStatus() {
var xhr = new XMLHttpRequest();
xhr.open("GET", "online.php", true);
xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

            // Insert online users into the onlineli div
            var onlineUsersHtml = "";
            response.online_users.forEach(function(user) {
                onlineUsersHtml += "<li>" + user.username + "# " + user.discriminator + "</li>";
            });
            document.getElementById("onlineli").innerHTML = onlineUsersHtml;

            // Insert offline users into the offlineli div
            var offlineUsersHtml = "";
            response.offline_users.forEach(function(user) {
                offlineUsersHtml += "<li>" + user.username + "#" + user.discriminator + "</li>";
            });
            document.getElementById("offlineli").innerHTML = offlineUsersHtml;
        } else {
            
        }
    }
};
xhr.send();
}

// Call fetchUserStatus every 5 minutes
setInterval(fetchUserStatus, 5 * 60 * 1000);