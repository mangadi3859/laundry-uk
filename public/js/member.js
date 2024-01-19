const deleleBtn = document.querySelectorAll("[data-action-delete]");

deleleBtn.forEach((e) => {
    e.addEventListener("click", async () => {
        try {
            let confirm = await DangerConfirm.fire({
                title: "Konfirmasi penghapusan data",
                html: `Member bernama <strong class="accent">${e.dataset.member}</strong> akan dihapus`,
                footer: "Semua data yang bersangkutan akan ikut terhapus!",
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
                showProgressBar: true,
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
