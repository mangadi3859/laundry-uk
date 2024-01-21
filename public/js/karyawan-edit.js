const form = document.querySelector("#form");
const id = document.querySelector("#i-id");
const nama = document.querySelector("#i-nama");
const username = document.querySelector("#i-username");
const email = document.querySelector("#i-email");
const password = document.querySelector("#i-password");
const outlet = document.querySelector("#i-outlet");
const role = document.querySelector("#i-role");

form?.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (role.dataset.value != "admin" && role.value == "admin") {
        let confirm = await DangerConfirm.fire({
            title: "Role khusus",
            html: `User ini akan memiliki role <strong class="accent">${role.value}</strong>`,
            confirmButtonText: "Simpan",
            footer: `<strong class="accent">${role.value}</strong> memiliki hak akses tertinggi dari role lainya`,
        });

        if (!confirm.isConfirmed) return;
    }

    let confirm = await DangerConfirm.fire({
        icon: "warning",
        title: "Konfirmasi perubahan data",
        confirmButtonText: "Simpan",
        html: `Data user akan diubah`,
    });

    if (!confirm.isConfirmed) return;

    let res = await (
        await fetch("../../api/karyawan/edit.php", {
            method: "POST",
            body: JSON.stringify({
                id: id.value,
                nama: nama.value,
                username: username.value,
                email: email.value,
                password: password.value,
                role: role.value,
                outlet: outlet.value,
            }),
            headers: {
                "Content-Type": "application/json",
            },
        })
    ).json();

    if (res.status != "ok") {
        return Swal.fire({
            title: "Gagal",
            text: res.message,
            icon: "error",
            showCloseButton: true,
            confirmButtonText: "Ulang",
        });
    }

    await Swal.fire({
        title: "Selesai",
        text: "Data diubah",
        icon: "success",
        timer: 2000,
        showCloseButton: true,
        confirmButtonText: "OK",
        timerProgressBar: true,
    });

    window.location = "./";
});
