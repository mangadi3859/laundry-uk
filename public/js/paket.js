const outletFilter = document.querySelector("[data-filter-outlet]");
const rows = document.querySelectorAll("[data-outlet]");
const deleleBtn = document.querySelectorAll("[data-action-delete]");

outletFilter?.addEventListener("input", ({ target }) => {
    let value = target.value;
    if (!value) return rows.forEach((e) => e.classList.remove("hide"));
    rows.forEach((e) => {
        if (e.dataset.outlet == value) return e.classList.remove("hide");
        e.classList.add("hide");
    });
});

deleleBtn.forEach((e) => {
    e.addEventListener("click", async () => {
        try {
            let confirm = await DangerConfirm.fire({
                title: "Konfirmasi penghapusan data",
                html: `<strong class="accent">${e.dataset.paket}</strong> akan dihapus`,
                footer: "Paket ini belum dipakai di tabel mana pun.",
            });

            if (!confirm.isConfirmed) return;

            let req = await fetch("delete.php", {
                headers: {
                    "Content-Type": "application/json",
                },
                method: "POST",
                body: JSON.stringify({
                    id: e.dataset.actionDelete,
                }),
            });

            let res = await req.json();

            if (res.status != "ok") {
                return Swal.fire({
                    icon: "error",
                    title: "Proses Gagal",
                    text: res.message,
                    showCloseButton: true,
                    confirmButtonText: "OK",
                });
            }

            await Swal.fire({
                icon: "success",
                title: "Data dihapus",
                confirmButtonText: "OK",
                timer: 2000,
                timerProgressBar: true,
            });

            window.location.reload();
        } catch (err) {
            return Swal.fire({
                icon: "error",
                title: "Proses Gagal",
                text: err.toString(),
                showCloseButton: true,
                confirmButtonText: "OK",
            });
        }
    });
});
