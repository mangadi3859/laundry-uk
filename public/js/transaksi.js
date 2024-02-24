const editStatus = document.querySelectorAll("[data-status-edit]");
const editPembayaran = document.querySelectorAll("[data-pembayaran-edit]");
const nameForm = document.querySelector("[data-member-form]");
const outletFilter = document.querySelector("[data-filter-outlet]");
const memberFilter = document.querySelector("[data-filter-member]");
const deleleBtn = document.querySelectorAll("[data-action-delete]");
const rows = document.querySelectorAll("tr[data-outlet]");
const warnings = document.querySelectorAll("[data-warning]");
const tbody = document.querySelector("[data-table-body]");
let items = [];

moment.tz.setDefault("Asia/Makassar");
moment.locale("id");

initTable();

warnings.forEach((el) =>
    el.addEventListener("click", (ev) => {
        handleWarningClick(ev.target);
    })
);

function handleWarningClick(ev) {
    Swal.fire({
        title: "Warning",
        icon: "info",
        html: `Batas waktu sudah terlewat <strong class="accent">${moment(new Date(ev.dataset.warning)).fromNow()}</strong>`,
        showCloseButton: true,
    });
}

editStatus.forEach((el) => el.addEventListener("click", async (ev) => handleEditStatus(ev.target)));

async function handleEditStatus(ev) {
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
            Swal.getInput().value = ev.dataset.infoValue;
        },
    });

    if (!status.isConfirmed) return;

    let res = await (
        await fetch("../../api/transaksi/change-status.php", {
            method: "POST",
            body: JSON.stringify({
                id: ev.dataset.statusEdit,
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
}

editPembayaran.forEach((el) => el.addEventListener("click", async (ev) => handleEditPembayaran(ev.target)));

async function handleEditPembayaran(ev) {
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
            Swal.getInput().value = ev.dataset.infoValue;
        },
    });

    if (!status.isConfirmed) return;

    let res = await (
        await fetch("../../api/transaksi/change-payment.php", {
            method: "POST",
            body: JSON.stringify({
                id: ev.dataset.pembayaranEdit,
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
}

outletFilter?.addEventListener("input", ({ target }) => {
    let value = target.value;
    if (!value) {
        items.forEach((e) => delete e.OUTLET_FILTER);
        updateTable();
        return rows.forEach((e) => e.classList.remove("filter-outlet-hide"));
    }
    rows.forEach((e) => {
        if (e.dataset.outlet == value) return e.classList.remove("filter-outlet-hide");
        e.classList.add("filter-outlet-hide");
    });

    items.forEach((e) => {
        if (e.id_outlet == value) return delete e.OUTLET_FILTER;
        e.OUTLET_FILTER = true;
    });

    updateTable();
});

memberFilter?.addEventListener("input", ({ target }) => {
    let value = target.value;
    if (!value) {
        items.forEach((e) => delete e.MEMBER_FILTER);
        updateTable();
        return rows.forEach((e) => e.classList.remove("filter-member-hide"));
    }
    rows.forEach((e) => {
        if (e.dataset.member == value) return e.classList.remove("filter-member-hide");
        e.classList.add("filter-member-hide");
    });

    items.forEach((e) => {
        if (e.id_member == value) return delete e.MEMBER_FILTER;
        e.MEMBER_FILTER = true;
    });

    updateTable();
});

nameForm?.addEventListener("submit", (el) => {
    el.preventDefault();

    let value = el.target.querySelector("#i-name").value;
    if (!value) {
        items.forEach((e) => delete e.INVOICE_FILTER);
        updateTable();
        return rows.forEach((e) => e.classList.remove("filter-name-hide"));
    }
    rows.forEach((e) => {
        if (e.dataset.memberName.toLowerCase() == value.toLowerCase()) return e.classList.remove("filter-name-hide");
        e.classList.add("filter-name-hide");
    });

    items.forEach((e) => {
        if (e.invoice.toLowerCase() == value.toLowerCase()) return delete e.INVOICE_FILTER;
        e.INVOICE_FILTER = true;
    });

    updateTable();
});

deleleBtn.forEach((e) => {
    e.addEventListener("click", async () => handleDeleteBtnClick(e.target));
});

async function handleDeleteBtn(ev) {
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
                id: ev.dataset.actionDelete,
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
}

async function initTable() {
    let res = await (await fetch("../../api/get-transaction.php")).json();
    items = res;

    updateTable();
}

async function updateTable() {
    $("#pagination").pagination({
        dataSource: items.filter((f) => !f.OUTLET_FILTER && !f.MEMBER_FILTER && !f.INVOICE_FILTER),
        pageSize: 6,
        className: "paginationjs-theme-yellow paginationjs-big",
        callback: (data, pagination) => {
            tbody.innerHTML = "";
            let html = ``;

            data.forEach((row, i) => {
                let warning = parseInt(row.warning);
                let idMember = row.id_member;
                let idOutlet = row.id_outlet;

                let _tgl = new Date(row.tgl);
                let tgl = moment(_tgl).format("YYYY/MM/DD");

                let _tglBayar = new Date(row.tgl_bayar);
                let tglBayar = moment(_tglBayar).format("YYYY/MM/DD");

                let _batasWaktu = new Date(row.batas_waktu);
                let batasWaktu = moment(_batasWaktu).format("YYYY/MM/DD");

                let statusBG = "";
                let statusText = "";
                let statusBorder = "transparent";
                switch (row.status) {
                    case "baru": {
                        statusBorder = "#004a99";
                        statusBG = "#0062cc";
                        statusText = "white";
                        break;
                    }
                    case "proses": {
                        statusBorder = "#cc9a06";
                        statusBG = "#ffc720";
                        statusText = "black";
                        break;
                    }
                    case "selesai": {
                        statusBorder = "#186429";
                        statusBG = "#24963e";
                        statusText = "white";
                        break;
                    }
                    default: {
                        statusBorder = "#565e64";
                        statusBG = "#6c757d";
                        statusText = "white";
                        break;
                    }
                }

                let paymentBG = "";
                let paymentText = "";
                let paymentBorder = "transparent";
                switch (row.dibayar) {
                    case "belum_dibayar": {
                        paymemntBorder = "#cc9a06";
                        paymentBG = "#ffc720";
                        paymentText = "black";
                        break;
                    }
                    default: {
                        paymentBorder = "#186429";
                        paymentBG = "#24963e";
                        paymentText = "white";
                        break;
                    }
                }

                html += `<tr data-outlet='${idOutlet}' data-member='${idMember}' data-member-name='${row.invoice}'>`;
                html += `
                    <td>${i + 1 + (pagination.pageNumber - 1) * 6}</td>
                    <td><a ${warning && `data-warning='${row["batas_waktu"]}' onclick="handleWarningClick(this)"`} title='Batas waktu terlewat' class='${warning && `warning fa-triangle-exclamation fas`}'></a>${row.invoice}</td>
                    <td>${row.outlet}</td>
                    <td>${row.member}</td>
                    <td>${row.dibayar == "dibayar" ? tglBayar : "-"}</td>
                    <td><div class='td-info'><span style='color: ${statusText}; background-color: ${statusBG}; padding: .25rem .5rem; border-radius: .25rem; border: 1px solid ${statusBorder};'>${
                    row.status
                }</span> <button onclick="handleEditStatus(this)" data-info-value='${row.status}' data-status-edit='${row.id}' class='status-edit-btn fa fa-pen-to-square'></button></div></td>
                    <td><div class='td-info'><span style='color: ${paymentText}; background-color: ${paymentBG}; padding: .25rem .5rem; border-radius: .25rem; border: 1px solid ${paymentBorder};'>${
                    row.dibayar
                }</span> <button onclick="handleEditPembayaran(this)" data-info-value='${row.dibayar}' data-pembayaran-edit='${row.id}' class='status-edit-btn fa fa-pen-to-square'></button></div></td>
                <td class="tb-action">
                    <a href='view.php?id=${row["id"]}' title="VIEW DATA" class='action-btn btn-accent fa-eye fas'></a>
                    ${tbody.dataset.tableBody == "admin" ? `<a href='edit.php?id=${row["id"]}' title='EDIT DATA' class='action-btn btn-primary fas fa-gear'></a>` : ""}
                    <a onclick="handleDeleteBtn(this)" data-action-delete="${row["id"]}" title="HAPUS DATA" class='action-btn btn-danger fas fa-trash'></a>
                </td>
                `;
                html += `</tr>`;
            });

            tbody.innerHTML = html;
        },
    });
}
