function switchConnectionTab(tab) {
    // Remove active class from all tab buttons
    document.querySelectorAll(".connection-tab").forEach((button) => {
        button.classList.remove("active");
        // Add active class to the clicked tab button
        if (button.dataset.tab === tab) {
            button.classList.add("active");
        }
    });

    // Hide all tab contents
    document.querySelectorAll(".connection-content").forEach((content) => {
        content.classList.add("hidden");
    });

    // Show the content of the selected tab
    const selectedContent = document.getElementById(`${tab}Content`);
    if (selectedContent) {
        selectedContent.classList.remove("hidden");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Set default active tab on page load
    switchConnectionTab("followers");
});

function toggleFollowButton(event, button) {
    // event.preventDefault();

    const isFollowing = button.getAttribute("data-following") === "true";

    if (isFollowing) {
        // Currently following, toggle to not following
        button.textContent = "Follow";
        button.classList.remove(
            "border",
            "border-gray-300",
            "text-gray-700",
            "hover:bg-gray-50"
        );
        button.classList.add("bg-black", "text-white", "hover:bg-gray-800");
        button.setAttribute("data-following", "false");
    } else {
        // Currently not following, toggle to following
        button.textContent = "Following";
        button.classList.remove("bg-black", "text-white", "hover:bg-gray-800");
        button.classList.add(
            "border",
            "border-gray-300",
            "text-gray-700",
            "hover:bg-gray-50"
        );
        button.setAttribute("data-following", "true");
    }
}
