function switchAdminTab(tab) {
    document.querySelectorAll('.admin-tab').forEach(button => {
        button.classList.remove('active');
        if (button.dataset.tab === tab) {
            button.classList.add('active');
        }
    });
    
    //hides all content
    document.querySelectorAll('.admin-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    //shows content depending on which tab is active
    const selectedContent = document.getElementById(tab + 'Content');
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    //default tab on load
    switchAdminTab('users');
});