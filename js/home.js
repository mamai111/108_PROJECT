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
//delete dropdown
        function toggleDropdown(btn) {
            btn.nextElementSibling.style.display = btn.nextElementSibling.style.display === "block" ? "none" : "block";
        }

        function savePost(link) {
            alert("Post saved!");
            link.closest('.options-dropdown').style.display = 'none';
        }

        function deletePost(link) {
            if (confirm("Are you sure you want to delete this post?")) {
                link.closest('.post').remove();
            }
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.options-btn')) {
                var dropdowns = document.getElementsByClassName("options-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }