const outletFilter = document.querySelector("[data-filter-outlet]");
const rows = document.querySelectorAll("[data-outlet]");
const prohibited = document.querySelectorAll("[data-user-prohibited]");

outletFilter?.addEventListener("input", ({ target }) => {
    let value = target.value;
    if (!value) return rows.forEach((e) => e.classList.remove("hide"));
    rows.forEach((e) => {
        if (e.dataset.outlet == value) return e.classList.remove("hide");
        e.classList.add("hide");
    });
});

prohibited.forEach((el) =>
    el.addEventListener("click", (e) => {
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: "Tidak dapat mengubah data milik kamu sendiri",
            footer: "Kontak admin yang lain jika memang diperlukan",
        });
    })
);
