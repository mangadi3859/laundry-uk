const editStatus = document.querySelectorAll("[data-status-edit]");
const editPembayaran = document.querySelectorAll("[data-pembayaran-edit]");
const nameForm = document.querySelector("[data-member-form]");
const outletFilter = document.querySelector("[data-filter-outlet]");
const memberFilter = document.querySelector("[data-filter-member]");
const rows = document.querySelectorAll("tr[data-outlet]");

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
        if (e.dataset.memberName.toLowerCase().includes(value)) return e.classList.remove("filter-name-hide");
        e.classList.add("filter-name-hide");
    });
});
