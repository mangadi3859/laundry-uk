const paketBtn = document.querySelector("[data-input-paket]");
const qtyBtn = document.querySelector("[data-input-qty]");
const preview = document.querySelector("[data-preview]");
const previewRow = document.querySelector("[data-preview-row]");
const firstRow = document.querySelector("[data-first-row]");
const tableDetail = document.querySelector("[data-table-detail]");
const submitBtn = document.querySelector("[data-submit]");
const extraBtn = document.querySelector("[data-input-tambahan]");
const extraCol = document.querySelector("[data-col-extra]");
const subTotalCol = document.querySelector("[data-col-subtotal]");
const pajakCol = document.querySelector("[data-col-pajak]");
const diskonCol = document.querySelector("[data-col-diskon]");
const totalCol = document.querySelector("[data-col-total]");

const items = [];
let cur;
let extraPayment = 0;
let paketInput = paketBtn.parentNode.querySelector("select");
let qtyInput = qtyBtn.parentNode.querySelector("[data-input]");
let extraInput = extraBtn.parentNode.querySelector("[data-input]");

paketBtn.addEventListener("click", async (e) => {
    let outlet = paketInput.dataset.outlet;

    if (items.some((e) => e.id == paketInput.value)) {
        return Swal.fire({
            title: "Gagal",
            text: "Paket tersebut sudah dimasukan.",
            icon: "error",
            footer: "Pencet tombol edit untuk mengubah data",
            showCloseButton: true,
            confirmButtonText: "Tutup",
        });
    }

    let paket = await getPaket(paketInput.value, outlet);
    if (paket.status != "ok") {
        return Swal.fire({
            title: "Gagal",
            text: paket.message,
            icon: "error",
            showCloseButton: true,
            confirmButtonText: "Tutup",
        });
    }

    cur = paket.data;
    qtyBtn.parentNode.style.display = "";
    preview.style.display = "";

    handlePreview();
});

qtyBtn.addEventListener("click", (e) => {
    if (!cur) return;
    items.push({ ...cur, qty: parseInt(qtyInput.value) || 1 });
    handleItems();

    qtyBtn.parentNode.style.display = "none";
    preview.style.display = "none";
    paketInput.value = "";
    qtyInput.value = "1";
});

qtyInput.addEventListener("input", handlePreview);

extraBtn.addEventListener("click", () => {
    extraPayment = parseInt(extraInput.value || 0);
    extraInput.value = "";
    handleItems();
});

//UTILS

async function getPaket(id, outlet) {
    return await (
        await fetch("../../api/get-paket.php", {
            headers: { "Content-Type": "application/json" },
            method: "POST",
            body: JSON.stringify({
                id,
                outlet,
            }),
        })
    ).json();
}

function handlePreview() {
    if (!cur) return;
    let qty = parseInt(qtyInput.value) || 1;

    previewRow.innerHTML = `
    <tr>
        <td>${cur.id}</td>
        <td>${cur.nama_paket}</td>
        <td>${cur.jenis}</td>
        <td style="text-align: center;">${qty}</td>
        <td>Rp. ${parseInt(cur.harga).toLocaleString()}</td>
        <td>Rp. ${(parseInt(cur.harga) * qty).toLocaleString()}</td>
    </tr>
    `;
}

function handleItems() {
    tableDetail.querySelectorAll("[data-detail]").forEach((e) => e.remove());

    if (items.length > 0) {
        submitBtn.parentElement.style.display = "";
    } else submitBtn.parentElement.style.display = "none";

    items.forEach((e, i) => {
        let tr = document.createElement("tr");
        let html = `
        <td>${i + 1}</td>
        <td>${e.nama_paket}</td>
        <td style="text-align: center;">${e.qty}</td>
        <td>Rp. ${parseInt(e.harga).toLocaleString()}</td>
        <td>Rp. ${(parseInt(e.harga) * e.qty).toLocaleString()}</td>
        <td class="tb-action">
            <button onclick="handleDelete(this)" data-index="${i}" title="HAPUS DATA" class='action-btn btn-danger fas fa-trash'></button>
            <button onclick="handleEdit(this)" data-index="${i}" data-action-edit="${i}" title="EDIT DATA" class='action-btn btn-primary fas fa-gear'></button>
        </td>
        `;

        tr.dataset.detail = "";
        tr.innerHTML = html;
        tableDetail.insertBefore(tr, firstRow);
    });

    let subtotal = items.reduce((pre, now) => pre + now.qty * now.harga, 0);
    let diskon = (subtotal + extraPayment) * parseFloat(diskonCol.dataset.colDiskon);
    let pajak = (subtotal + extraPayment) * parseFloat(pajakCol.dataset.colPajak);
    let total = subtotal + extraPayment - diskon + pajak;
    subTotalCol.innerText = `Rp. ${diskon.toLocaleString()}`;
    subTotalCol.innerText = `Rp. ${subtotal.toLocaleString()}`;
    diskonCol.innerText = `Rp. ${diskon.toLocaleString()}`;
    pajakCol.innerText = `Rp. ${pajak.toLocaleString()}`;
    extraCol.innerText = `Rp. ${extraPayment.toLocaleString()}`;
    totalCol.innerText = `Rp. ${total.toLocaleString()}`;
}

function handleDelete(element) {
    if (!("index" in element.dataset)) return;
    // delete items[parseInt(element.dataset.index)];
    items.splice(parseInt(element.dataset.index), parseInt(element.dataset.index) + 1);
    handleItems();
}

function handleEdit(element) {
    if (!("index" in element.dataset)) return;
    let old = items[parseInt(element.dataset.index)];
    handleDelete(element);

    cur = old;
    handlePreview();
    paketInput.value = cur.id;
    qtyInput.value = cur.qty;

    qtyBtn.parentNode.style.display = "";
    preview.style.display = "";
}
