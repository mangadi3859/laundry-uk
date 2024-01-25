const firstRow = document.querySelector("[data-first-row]");
const tableDetail = document.querySelector("[data-table-detail]");
const idMember = document.querySelector("[data-member]").dataset.member;
const idOutlet = document.querySelector("[data-outlet]").dataset.outlet;
const payment = document.querySelector("[data-payment]").dataset.payment;
const _status = document.querySelector("[data-status]").dataset.status;

const items = [];
let cur;
let extraPayment = 0;

//UTILS
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
