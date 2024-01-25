const editStatus = document.querySelectorAll("[data-status-edit]");
const editPembayaran = document.querySelectorAll("[data-pembayaran-edit]");
const nameForm = document.querySelector("[data-member-form]");
const outletFilter = document.querySelector("[data-filter-outlet]");
const memberFilter = document.querySelector("[data-filter-member]");
const deleleBtn = document.querySelectorAll("[data-action-delete]");
const rows = document.querySelectorAll("tr[data-outlet]");
const warnings = document.querySelectorAll("[data-warning]");

moment.tz.setDefault("Asia/Makassar");
moment.locale("id");

warnings.forEach((el) =>
    el.addEventListener("click", (ev) => {
        Swal.fire({
            title: "Warning",
            icon: "info",
            html: `Batas waktu sudah terlewat <strong class="accent">${moment(new Date(ev.target.dataset.warning)).fromNow()}</strong>`,
            showCloseButton: true,
        });
    })
);

editStatus.forEach((el) =>
    el.addEventListener("click", async (ev) => {
        let status = await Swal.fire({
            title: "Perubahan <strong class='accent'>Status</strong> transaksi",
            input: "select",
            inputOptions: {
                proses: "🟡 Proses",
                baru: "🔵 Baru",
                selesai: "🟢 Selesai",
                diambil: "⚪ Diambil",
            },
            inputPlaceholder: "Pilih status",
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Simpan",
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (!value) {
                        resolve("Input tidak bisa kosong");
                    }
                    resolve();
                });
            },

            didOpen: () => {
                Swal.getInput().value = ev.target.dataset.infoValue;
            },
        });

        if (!status.isConfirmed) return;

        let res = await (
            await fetch("../../api/transaksi/change-status.php", {
                method: "POST",
                body: JSON.stringify({
                    id: ev.target.dataset.statusEdit,
                    status: status.value,
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
            title: "Status diganti",
            icon: "success",
            showCloseButton: true,
            timer: 2000,
            timerProgressBar: true,
        });

        window.location.reload();
    })
);

editPembayaran.forEach((el) =>
    el.addEventListener("click", async (ev) => {
        let status = await Swal.fire({
            title: "Perubahan <strong class='accent'>Status Pembayaran</strong> transaksi",
            input: "select",
            inputOptions: {
                belum_dibayar: "🟡 Belum Bayar",
                dibayar: "🟢 Dibayar",
            },
            inputPlaceholder: "Pilih status pembayaran",
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Simpan",
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (!value) {
                        resolve("Input tidak bisa kosong");
                    }
                    resolve();
                });
            },

            didOpen: () => {
                Swal.getInput().value = ev.target.dataset.infoValue;
            },
        });

        if (!status.isConfirmed) return;

        let res = await (
            await fetch("../../api/transaksi/change-payment.php", {
                method: "POST",
                body: JSON.stringify({
                    id: ev.target.dataset.pembayaranEdit,
                    status: status.value,
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
            title: "Status diganti",
            icon: "success",
            showCloseButton: true,
            timer: 2000,
            timerProgressBar: true,
        });

        window.location.reload();
    })
);

outletFilter?.addEventListener("input", ({ target }) => {
    let value = target.value;
    if (!value) return rows.forEach((e) => e.classList.remove("filter-outlet-hide"));
    rows.forEach((e) => {
        if (e.dataset.outlet == value) return e.classList.remove("filter-outlet-hide");
        e.classList.add("filter-outlet-hide");
    });
});

memberFilter?.addEventListener("input", ({ target }) => {
    let value = target.value;
    if (!value) return rows.forEach((e) => e.classList.remove("filter-member-hide"));
    rows.forEach((e) => {
        if (e.dataset.member == value) return e.classList.remove("filter-member-hide");
        e.classList.add("filter-member-hide");
    });
});

nameForm?.addEventListener("submit", (el) => {
    el.preventDefault();

    let value = el.target.querySelector("#i-name").value;
    if (!value) return rows.forEach((e) => e.classList.remove("filter-name-hide"));
    rows.forEach((e) => {
        if (e.dataset.memberName.toLowerCase() == value.toLowerCase()) return e.classList.remove("filter-name-hide");
        e.classList.add("filter-name-hide");
    });
});

deleleBtn.forEach((e) => {
    e.addEventListener("click", async () => {
        try {
            let confirm = await DangerConfirm.fire({
                title: "Konfirmasi penghapusan data",
                html: `Data transaksi ini akan dihapus`,
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
