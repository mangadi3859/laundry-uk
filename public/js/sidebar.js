const logoutButton = document.querySelector("#logoutBtn");

logoutButton.addEventListener("click", async (e) => {
    let logout = await Swal.fire({
        icon: "warning",
        title: "Logout",
        text: "Are you sure want to Logout?",
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonText: "Logout",
        cancelButtonText: "Cancel",
        focusConfirm: false,
    });

    if (logout.isConfirmed) {
        window.location = logoutButton.dataset.logoutPath;
    }
});
