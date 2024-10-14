window.onload = function() {
    attachCommentFormSubmitListeners();
};

function attachCommentFormSubmitListeners() {
    document.querySelectorAll('.add-comment-form').forEach(form => {
        form.removeEventListener('submit', handleCommentFormSubmit);
        form.addEventListener('submit', handleCommentFormSubmit);
    });
}

function handleCommentFormSubmit(event) {
    event.preventDefault();

    const form = event.target;
    const post = form.querySelector('input[name="post"]').value;
    const text = form.querySelector('textarea[name="text"]').value;
    const repliedToElement = form.querySelector('input[name="repliedTo"]');
    const repliedTo = repliedToElement ? repliedToElement.value : "";
    const token = form.querySelector('input[name="token"]').value;

    addComment(post, text, repliedTo, form, token);
}

function addComment(post, text, repliedTo, form, token) {
    const request = new XMLHttpRequest();

    request.open('POST', '../actions/action_add_comment.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onload = function() {
        if (request.status >= 200 && request.status < 400) {
            const response = JSON.parse(request.responseText);
            if (response.error) {
                console.log("Error adding comment: " + response.error);
                return;
            }

            // Create new comment elements
            const newComment = document.createElement('div');
            newComment.classList.add('comment-section');
            newComment.setAttribute('data-id', response.id);

            const commentInfo = document.createElement('div');
            commentInfo.classList.add('comment-info');

            const authorPhoto = document.createElement('img');
            authorPhoto.src = response.authorPhoto;
            authorPhoto.alt = 'author photo';

            const authorName = document.createElement('h3');
            authorName.textContent = response.authorName;

            const timestamp = document.createElement('h5');
            timestamp.textContent = timeAgo(response.timestamp);

            const commentText = document.createElement('h3');
            commentText.textContent = text;

            commentInfo.appendChild(authorPhoto);
            commentInfo.appendChild(authorName);
            commentInfo.appendChild(timestamp);
            newComment.appendChild(commentInfo);
            newComment.appendChild(commentText);

            const repliesDiv = document.createElement('div');
            repliesDiv.classList.add('replies');
            newComment.appendChild(repliesDiv);

            const replyForm = document.createElement('form');
            replyForm.method = 'post';
            replyForm.classList.add('add-comment-form', 'reply-form');
            replyForm.id = `replyForm-${response.id}`;
            replyForm.style.display = 'none';

            const postInput = document.createElement('input');
            postInput.type = 'hidden';
            postInput.name = 'post';
            postInput.value = post;

            const textArea = document.createElement('textarea');
            textArea.name = 'text';

            const repliedToInput = document.createElement('input');
            repliedToInput.type = 'hidden';
            repliedToInput.name = 'repliedTo';
            repliedToInput.value = response.id;

            const submitButton = document.createElement('button');
            submitButton.type = 'submit';
            submitButton.classList.add('save-button');
            submitButton.textContent = 'Send Comment';

            replyForm.appendChild(postInput);
            replyForm.appendChild(textArea);
            replyForm.appendChild(repliedToInput);
            replyForm.appendChild(submitButton);

            newComment.appendChild(replyForm);

            const replyButton = document.createElement('button');
            replyButton.classList.add('reply-button');
            replyButton.id = `replyButton-${response.id}`;
            replyButton.textContent = 'Reply';
            replyButton.setAttribute('onclick', `toggleReplyForm(${response.id})`);
            newComment.appendChild(replyButton);

            // Check if it's a reply
            if (repliedTo) {
                const parentComment = document.querySelector(`.comment-section[data-id='${repliedTo}'] .replies`);
                if (parentComment) {
                    parentComment.appendChild(newComment);
                } else {
                    console.error('Parent comment not found');
                }
            } else {
                const commentSection = document.querySelector('.comments');
                commentSection.appendChild(newComment);
            }

            form.querySelector('textarea[name="text"]').value = '';

            attachCommentFormSubmitListeners();
        } else {
            console.log("Error adding comment: " + request.responseText);
        }
    };

    request.onerror = function() {
        console.log("Connection error");
    };

    const data = `post=${encodeURIComponent(post)}&text=${encodeURIComponent(text)}&repliedTo=${encodeURIComponent(repliedTo)}&token=${encodeURIComponent(token)}`;
    request.send(data);
}

function toggleReplyForm(commentId) {
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

function timeAgo(datetime) {
    const now = new Date();
    const past = new Date(datetime);
    const diffInSeconds = Math.floor((now - past) / 1000);

    const intervals = {
        year: 365*24*60*60,
        month: 30*24*60*60,
        week: 7*24*60*60,
        day: 60*60*24,
        hour: 60*60,
        minute: 60,
        second: 1
    };

    let counter;

    for (const [unit, secondsInUnit] of Object.entries(intervals)) {
        counter = Math.floor(diffInSeconds / secondsInUnit);
        if (counter > 0) {
            if (counter === 1) {
                return `${counter} ${unit} ago`; 
            } else {
                return `${counter} ${unit}s ago`; 
            }
        }
    }

    return 'just now';
}
