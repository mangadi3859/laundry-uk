const logoutButton = document.querySelectorAll("[data-logout-btn]");

logoutButton.forEach((el) =>
    el.addEventListener("click", async (e) => {
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
            window.location = el.dataset.logoutPath;
        }
    })
);
