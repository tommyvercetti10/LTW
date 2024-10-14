function deletePost(id) {
    const request = new XMLHttpRequest(); 
    request.open('POST', '../actions/action_post.php', true); 
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    request.onload = function() {
        if (request.status >= 200 && request.status < 400) {
            console.log(request.responseText); 
            const element = document.querySelector(`.item-preview-section[post-id='${id}']`);
            if (element) {
                element.remove();
            } else {
                console.log('Element not found in the DOM');
            }
        } else {
            console.log("Error deleting Post: " + request.responseText);
        }
    };

    request.onerror = function() {
        console.log("Connection error");
    };

    request.send('id=' + id); 
}

function deletePostFromWishlist(id) {
    const request = new XMLHttpRequest(); 
    request.open('POST', '../actions/action_post.php', true);  
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    request.onload = function() {
        if (request.status >= 200 && request.status < 400) {
            console.log(request.responseText); 
            const element = document.querySelector(`.item-preview-section[post-id='${id}']`);
            if (element) {
                element.remove();
            } else {
                console.log('Element not found in the DOM');
            }
        } else {
            console.log("Error deleting Post: " + request.responseText);
        }
    };

    request.onerror = function() {
        console.log("Connection error");
    };

    request.send('id=' + id); 
}

function deletePostFromCart(id) {
    const request = new XMLHttpRequest(); 
    request.open('POST', '../actions/action_post.php', true); 
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    request.onload = function() {
        if (request.status >= 200 && request.status < 400) {
            console.log(request.responseText); 
            const element = document.querySelector(`.item-preview-section[post-id='${id}']`);
            if (element) {
                element.remove();
            } else {
                console.log('Element not found in the DOM');
            }
        } else {
            console.log("Error deleting Post: " + request.responseText);
        }
    };

    request.onerror = function() {
        console.log("Connection error");
    };

    request.send('id=' + id); 
}