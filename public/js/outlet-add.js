const form = document.querySelector("#form");
const nama = document.querySelector("#i-nama");
const alamat = document.querySelector("#i-alamat");
const nohp = document.querySelector("#i-nohp");

form?.addEventListener("submit", async (e) => {
    e.preventDefault();

    let res = await (
        await fetch("../../api/outlet/add.php", {
            method: "POST",
            body: JSON.stringify({
                nama: nama.value,
                alamat: alamat.value,
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
        text: "Data ditambah",
        icon: "success",
        timer: 2000,
        showCloseButton: true,
        confirmButtonText: "OK",
        timerProgressBar: true,
    });

    window.location = "./";
});
