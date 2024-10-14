document.getElementById('editItemForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    const request = new XMLHttpRequest();
    request.open('POST', '../actions/action_update_post.php', true);

    request.onload = function() {
        if (request.status >= 200 && request.status < 400) {
            const response = JSON.parse(request.responseText);
            if (response.status === 'success') {
                window.location.href = '../pages/profile.php';
            } else {
                console.error("Error updating Post" );
            }
        } else {
            console.error("Error updating Post");
        }
    };

    request.onerror = function() {
        console.error("Connection error");
    };

    request.send(formData);
});


function toggleEditPost() {
    const replyForm = document.getElementById(`replyForm-${commentId}`);
    const replyButton = document.getElementById(`replyButton-${commentId}`);
    if (replyForm && replyButton) { 
        if (replyForm.style.display === 'none' || replyForm.style.display === '') {
            replyForm.style.display = 'block';
            replyButton.textContent = 'Cancel';
        } else {
            replyForm.style.display = 'none';
            replyButton.textContent = 'Reply';
        }
    } else {
        console.error('NOT FOUND');
    }
}