const form = document.querySelector("#form");
const id = document.querySelector("#i-id");
const member = document.querySelector("#i-member");
const tgl = document.querySelector("#i-tgl");
const deadline = document.querySelector("#i-batas");
const user = document.querySelector("#i-kasir");
const extra = document.querySelector("#i-extra");

form?.addEventListener("submit", async (e) => {
    e.preventDefault();

    let confirm = await DangerConfirm.fire({
        icon: "warning",
        title: "Konfirmasi perubahan data",
        confirmButtonText: "Simpan",
        html: `Data member akan diubah`,
    });

    if (!confirm.isConfirmed) return;

    let res = await (
        await fetch("../../api/transaksi/edit.php", {
            method: "POST",
            body: JSON.stringify({
                id: id.value,
                member: member.value,
                tgl: tgl.value,
                deadline: deadline.value,
                user: user.value,
                extra: extra.value,
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
