const form = document.querySelector("#form");
const id = document.querySelector("#i-id");
const nama = document.querySelector("#i-nama");
const alamat = document.querySelector("#i-alamat");
const gender = document.querySelector("#i-gender");
const nohp = document.querySelector("#i-nohp");

form?.addEventListener("submit", async (e) => {
    e.preventDefault();

    let confirm = await DangerConfirm.fire({
        icon: "warning",
        title: "Konfirmasi perubahan data",
        confirmButtonText: "Simpan",
        html: `Data member <strong class="accent">${nama.value}</strong> akan diubah`,
    });

    let res = await (
        await fetch("../../api/member/edit.php", {
            method: "POST",
            body: JSON.stringify({
                id: id.value,
                nama: nama.value,
                alamat: alamat.value,
                gender: gender.value,
                nohp: nohp.value,
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
