document.addEventListener("DOMContentLoaded", () => {
    const userIcon = document.getElementById("userIcon");
    const dropdown = document.getElementById("dropdownMenu");

    if (!userIcon || !dropdown) return;
    
    userIcon.addEventListener("click", (e) => {
        e.preventDefault();
        dropdown.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".user-menu")) { 
            dropdown.classList.remove("show");
        }
    });
});