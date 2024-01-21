const form = document.querySelector("#form");
const nama = document.querySelector("#i-nama");
const jenis = document.querySelector("#i-jenis");
const outlet = document.querySelector("#i-outlet");
const harga = document.querySelector("#i-harga");

form?.addEventListener("submit", async (e) => {
    e.preventDefault();

    let res = await (
        await fetch("../../api/paket/add.php", {
            method: "POST",
            body: JSON.stringify({
                nama: nama.value,
                jenis: jenis.value,
                outlet: outlet.value,
                harga: harga.value,
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
        text: "Data ditambah",
        icon: "success",
        timer: 2000,
        showCloseButton: true,
        confirmButtonText: "OK",
        timerProgressBar: true,
    });

    window.location = "./";
});
