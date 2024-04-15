const resetBtn = document.querySelector("[data-reset-pass]");
const avatarImg = document.querySelector("[data-avatar-img]");
const inputAvatar = document.querySelector("[data-avatar-input]");
const avatarSaveBtn = document.querySelector("[data-avatar-save]");
const avatarCancelBtn = document.querySelector("[data-avatar-cancel]");
const avatarDeleteBtn = document.querySelector("[data-avatar-delete]");
let avatar;
let tmpAvatar;

resetBtn.addEventListener("click", async () => {
    let res = await Swal.fire({
        title: "Masukan password kamu",
        text: "Masukan password kamu yang sekarang",
        icon: "question",
        input: "password",
        // inputLabel: "Password",
        showCloseButton: true,
        confirmButtonText: "Kirim",
        inputPlaceholder: "Password kamu",
        inputValidator: (v) => {
            if (!v) return "Masukan password";
        },
        inputAttributes: {
            autocapitalize: "off",
            autocorrect: "off",
        },
        footer: "Hubungi admin jika lupa password",
    });

    if (!res.value) return;

    let verify = await fetch("../api/verify.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ password: res.value }),
    });

    if ((await verify.json()).status == "failed") {
        return Swal.fire({
            title: "Gagal",
            text: "Password salah",
            icon: "error",
            showCloseButton: true,
            confirmButtonText: "Tutup",
            footer: "Hubungi admin jika lupa password",
        });
    }

    let pw = await Swal.fire({
        title: "Ganti password",
        text: "Masukan password baru kamu",
        icon: "warning",
        input: "password",
        // inputLabel: "Password",
        showCloseButton: true,
        confirmButtonText: "Kirim",
        inputPlaceholder: "Password kamu",
        inputValidator: (v) => {
            if (!v) return "Masukan password";
        },
        inputAttributes: {
            autocapitalize: "off",
            autocorrect: "off",
        },
        footer: "Kamu akan logout secara otomatis",
    });

    if (!pw.isConfirmed || !pw.value) return;
    let resetPas = await (
        await fetch("../api/change-password.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ old_pw: res.value, password: pw.value }),
        })
    ).json();

    if (resetPas.status == "failed") {
        return Swal.fire({
            title: "Gagal",
            text: "Proses gagal. coba lagi nanti.",
            icon: "error",
            showCloseButton: true,
            confirmButtonText: "Tutup",
        });
    }

    await Swal.fire({
        title: "Berhasil",
        text: "Password telah diganti",
        icon: "success",
        showCloseButton: true,
        confirmButtonText: "Tutup",
        footer: "Akun akan logout secara otomatis",
    });

    window.location = "../logout.php";
});

function readFile64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader(file);
        reader.onload = (e) => resolve(e.target.result);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

inputAvatar.addEventListener("change", async (e) => {
    let img = e.target.files[0];

    if (img?.name == tmpAvatar) return;
    if (avatar && !img) return avatarImg.setAttribute("src", avatar);
    if (!img) {
        avatarImg.setAttribute("src", avatarImg.dataset.default);
        avatar = null;
        tmpAvatar = null;
        avatarSaveBtn.parentNode.style.display = "none";
        return;
    }

    if (img.size / 1000 / 1000 > 1) {
        return Swal.fire({
            icon: "error",
            title: "Gagal",
            html: "Gambar tidak boleh melebihi ukuran <strong class='accent'>1MB</strong>",
            showCloseButton: true,
            confirmButtonText: "Ulang",
        });
    }

    try {
        let buffer = await readFile64(img);
        avatar = buffer;
        tmpAvatar = img.name;
        avatarImg.setAttribute("src", buffer);
        avatarSaveBtn.parentNode.style.display = "block";
    } catch (e) {
        console.error(e);

        Swal.fire({
            title: "Error",
            icon: "error",
            text: "Terjadi kesalahan saat memproses gambar",
            footer: "Buka console untuk melihat error",
            showCloseButton: true,
            confirmButtonText: "Ulang",
        });
    }
});

avatarCancelBtn.addEventListener("click", (ev) => {
    avatar = null;
    tmpAvatar = null;
    inputAvatar.value = "";
    avatarSaveBtn.parentNode.style.display = "none";
    avatarImg.setAttribute("src", avatarImg.dataset.default);
});

avatarSaveBtn.addEventListener("click", async (ev) => {
    let confirm = await DangerConfirm.fire({
        title: "Konfirmasi Penggantian Avatar",
        text: "Avatar lama kamu akan dihapus dari sistem",
        confirmButtonText: "Simpan",
    });

    if (!confirm.isConfirmed) return;
    let img = inputAvatar.files[0];

    if (!img) {
        Swal.fire({
            title: "Gagal",
            icon: "error",
            text: "Gambar belum ada",
            showCloseButton: true,
            confirmButtonText: "Ulang",
        });
    }

    let formData = new FormData();
    formData.append("avatar", img);

    let res = await (
        await fetch("../api/karyawan/change-avatar.php", {
            method: "POST",
            // headers: {
            //     "Content-Type": "multipart/form-data",
            // },
            body: formData,
        })
    ).json();

    if (res.status != "ok") {
        return Swal.fire({
            title: "Gagal",
            icon: "error",
            text: res.message,
            showCloseButton: true,
            confirmButtonText: "Ulang",
        });
    }

    await Swal.fire({
        icon: "success",
        title: "Avatar disimpan",
        timer: 2000,
        showTimerProgress: true,
    });

    window.location.reload();
});

avatarDeleteBtn.addEventListener("click", async (ev) => {
    let confirm = await DangerConfirm.fire({
        title: "Konfirmasi Penghapusan Avatar",
        text: "Avatar kamu akan dihapus dari sistem",
        confirmButtonText: "Hapus",
    });

    if (!confirm.isConfirmed) return;
    let formData = new FormData();
    formData.append("del", true);

    let res = await (
        await fetch("../api/karyawan/change-avatar.php", {
            method: "POST",
            body: formData,
        })
    ).json();

    if (res.status != "ok") {
        return Swal.fire({
            title: "Gagal",
            icon: "error",
            text: res.message,
            showCloseButton: true,
            confirmButtonText: "Ulang",
        });
    }

    await Swal.fire({
        icon: "success",
        title: "Avatar dihapus",
        timer: 2000,
        showTimerProgress: true,
    });

    window.location.reload();
});
