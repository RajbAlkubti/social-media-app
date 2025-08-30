function switchTab(tab) {
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
        if (button.dataset.tab === tab) {
            button.classList.add('active');
        }
    });
    
    //hides all content
    document.querySelectorAll('.posts').forEach(content => {
        content.classList.add('hidden');
    });
    
    //shows content depending on which tab is active
    const selectedContent = document.getElementById(tab + 'Post');
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    //default tab on load
    switchTab('following');
});