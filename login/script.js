window.onload = function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "check.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            console.log(response.message);
            if (response.message === "Cookie matches user in the database") {
                // Redirect to a website
                window.location.href = '/login/chat?chatid="main"';
            }
        }
    };
    xhr.send();
};


function submitUserData() {
    const radio5Checked = document.getElementById('radio5').checked;

    if (!radio5Checked) {
        console.error('Error: Radio button not selected');
        alert("promise to be nice")
        return; // Exit the function if the radio button is not selected
    }
    const username = document.getElementById('text22').value;
    const password = document.getElementById('text23').value;

    // Hash the password using SHA-256 algorithm
    crypto.subtle.digest('SHA-256', new TextEncoder().encode(password)).then(hashBuffer => {
        // Convert the hash buffer to hexadecimal string
        const hashedPassword = Array.prototype.map.call(new Uint8Array(hashBuffer), x => ('00' + x.toString(16)).slice(-2)).join('');

        // Construct the user data object
        const userData = {
            username: username,
            password: hashedPassword,
        };

        // Convert the data to JSON format
        const jsonData = JSON.stringify(userData);

        // Create a new XMLHttpRequest object
        const xhr = new XMLHttpRequest();

        // Define the request parameters
        const url = 'register.php';
        const method = 'POST';

        // Configure the request
        xhr.open(method, url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        // Define the callback function when the request completes
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Request was successful, handle the response
                const response = JSON.parse(xhr.responseText);
                window.location.replace('https://april.lexiqqq.com/login/chat?chatid="main"')
            } else {
                // Request failed, handle the error
                console.error('Request failed with status:', xhr.status);
            }
        };

        // Send the request with the JSON data
        xhr.send(jsonData);
    }).catch(error => {
        console.error('Error hashing password:', error);
    });
}
