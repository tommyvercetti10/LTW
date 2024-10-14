function getReceipt(posts) {

    const request = new XMLHttpRequest(); 
    request.open('POST', '../actions/action_get_receipt.php', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    request.onload = function() {
        if (request.status < 200 || request.status >= 400) {
            console.log("Error creating receipt: " + request.responseText);
        }
    };

    request.onerror = function() {
        console.log("Connection error");
    };

    request.send(JSON.stringify(posts)); 
}
