const numberOnly = document.querySelectorAll("[data-number-only]");
const popoverBtn = document.querySelectorAll("[data-action-popover]");
const dataValues = document.querySelectorAll("[data-value]");

numberOnly.forEach((e) => {
    e.addEventListener("input", (el) => {
        let regex = /\D/g;
        let target = el.target;
        if (!target.value.match(regex)) return;

        target.value = target.value.replace(regex, "");
    });
});

popoverBtn.forEach((el) =>
    el.addEventListener("click", (e) => {
        e.target.querySelector(".popover").classList.toggle("hide");
    })
);

let DangerConfirm = Swal.mixin({
    icon: "warning",
    // title: "Konfirmasi penghapusan data",
    // html: `Member bernama <strong class="accent">${e.dataset.member}</strong> akan dihapus`,
    showCloseButton: true,
    focusConfirm: false,
    showCancelButton: true,
    cancelButtonText: "Batal",
    confirmButtonText: "Hapus",
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#007bff",
    // footer: "Semua data yang bersangkutan akan ikut terhapus!",
});

dataValues.forEach((e) => {
    e.value = e.dataset.value;
});
