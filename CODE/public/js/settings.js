function switchSettingsTab(tab) {
    document.querySelectorAll('.settings-tab').forEach(button => {
        button.classList.remove('active');
        if (button.dataset.tab === tab) {
            button.classList.add('active');
        }
    });
    
    //hides all content
    document.querySelectorAll('.settings-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    //shows content depending on which tab is active
    const selectedContent = document.getElementById(tab + 'Settings');
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
}


document.addEventListener('DOMContentLoaded', function() {
    //default tab on load
    switchSettingsTab('profile');
});

    document.getElementById('profilePictureInput').addEventListener('change', function(event) {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = e => {
    // Update main form preview
    document.getElementById('profilePicturePreview').src = e.target.result;

    // Update sidebar preview
    const sidebarPic = document.getElementById('sidebarProfilePicturePreview');
    if (sidebarPic) {
      sidebarPic.src = e.target.result;
    }
  };
  reader.readAsDataURL(file);
});