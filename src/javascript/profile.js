function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('preview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function toggleEditProfile() {
    var editProfile = document.getElementById('edit-profile');
    var profile = document.getElementById('profile-section');
    var posts = document.getElementById('posts');
    if (editProfile.style.display === 'none' || editProfile.style.display === '') {
        editProfile.style.display = 'flex';
        editProfile.style.justifyContent = 'center';
        editProfile.style.alignItems = 'center';
        profile.style.display = 'none';
        posts.style.display = 'none';
    } else {
        editProfile.style.display = 'none';
        profile.style.display = 'flex';
        posts.style.display = 'flex';
    }
}

function toggleAddItem() {
    var adminChanges = document.getElementById('admin-changes');
    var addItem = document.getElementById('add-item');
    var profile = document.getElementById('profile-section');
    var posts = document.getElementById('posts');
    var editProfile = document.getElementById('edit-profile');
    if (addItem.style.display === 'none' || addItem.style.display === '') {
        addItem.style.display = 'flex';
        addItem.style.justifyContent = 'center';
        addItem.style.alignItems = 'center';
        profile.style.display = 'none';
        posts.style.display = 'none';
        editProfile.style.display = 'none'; 
        adminChanges.style.display = 'none';
    } else {
        addItem.style.display = 'none';
        profile.style.display = 'flex';
        posts.style.display = 'flex';

    }
}


function toggleAdminChanges() {
    var adminChanges = document.getElementById('admin-changes');
    var addItem = document.getElementById('add-item');
    var profile = document.getElementById('profile-section');
    var posts = document.getElementById('posts');
    var editProfile = document.getElementById('edit-profile');
    if (adminChanges.style.display === 'none' || addItem.style.display === '') {
        adminChanges.style.display = 'flex';
        adminChanges.style.justifyContent = 'center';
        adminChanges.style.alignItems = 'center';
        addItem.style.display = 'none';
        profile.style.display = 'none';
        posts.style.display = 'none';
        editProfile.style.display = 'none'; 
    } else {
        adminChanges.style.display = 'none';
        profile.style.display = 'flex';
        posts.style.display = 'flex';
    }
}