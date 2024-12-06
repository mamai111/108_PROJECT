// Get modal element
const modal = document.getElementById("myModal");
const registerBtn = document.getElementById("registerBtn");
const closeModal = document.getElementById("closeModal");

// Show the modal when the button is clicked
registerBtn.onclick = function() {
    modal.style.display = "flex"; // Use flex to center
}

// Close the modal when the x is clicked
closeModal.onclick = function() {
    modal.style.display = "none";
}

// Close the modal when clicking outside of the modal
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}