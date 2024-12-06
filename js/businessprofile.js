// Open edit profile modal
editBtn.onclick = function() {
    editModal.style.display = "flex"; // Show the modal
}

// Close edit profile modal
closeEditModal.onclick = function() {
    editModal.style.display = "none"; // Hide the modal
}

// Close modals when clicking outside of them
window.onclick = function(event) {
    if (event.target == businessModal) {
        businessModal.style.display = "none"; // Hide the modal
    } else if (event.target == editModal) {
        editModal.style.display = "none"; // Hide the modal
    }
}
