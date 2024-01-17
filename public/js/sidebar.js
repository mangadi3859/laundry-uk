const logoutButton = document.querySelector("#logoutBtn");

logoutButton.addEventListener("click", async (e) => {
    let logout = await Swal.fire({
        icon: "warning",
        title: "Logout",
        text: "Konfirmasi untuk logout",
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonText: "Logout",
        cancelButtonText: "Batal",
        focusConfirm: false,
        focusCancel: false,
    });

    if (logout.isConfirmed) {
        window.location = logoutButton.dataset.logoutPath;
    }
});
