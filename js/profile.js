
    // Get modal elements
    var businessModal = document.getElementById("businessRegistrationModal");
    var editModal = document.getElementById("editModal");
    
    // Get buttons to open modals
    var registerBtn = document.getElementById("registerBtn");
    var editBtn = document.getElementById("editBtn");
    
    // Get close buttons
    var closeBusinessModal = document.getElementById("closeBusinessModal");
    var closeEditModal = document.getElementById("closeEditModal");

    // Open business registration modal
    registerBtn.onclick = function() {
        businessModal.style.display = "flex"; // Show the modal
    }

    // Open edit profile modal
    editBtn.onclick = function() {
        editModal.style.display = "flex"; // Show the modal
    }

    // Close business registration modal
    closeBusinessModal.onclick = function() {
        businessModal.style.display = "none"; // Hide the modal
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
    

