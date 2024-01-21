const resetBtn = document.querySelector("[data-reset-pass]");

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
