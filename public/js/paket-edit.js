const form = document.querySelector("#form");
const id = document.querySelector("#i-id");
const nama = document.querySelector("#i-nama");
const jenis = document.querySelector("#i-jenis");
const outlet = document.querySelector("#i-outlet");
const harga = document.querySelector("#i-harga");

form?.addEventListener("submit", async (e) => {
    e.preventDefault();

    let confirm = await DangerConfirm.fire({
        icon: "warning",
        title: "Konfirmasi perubahan data",
        confirmButtonText: "Simpan",
        html: `Data paket akan diubah`,
    });

    if (!confirm.isConfirmed) return;

    let res = await (
        await fetch("../../api/paket/edit.php", {
            method: "POST",
            body: JSON.stringify({
                id: id.value,
                nama: nama.value,
                jenis: jenis.value,
                harga: harga.value,
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
