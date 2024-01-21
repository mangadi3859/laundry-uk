const outletFilter = document.querySelector("[data-filter-outlet]");
const rows = document.querySelectorAll("[data-outlet]");

outletFilter?.addEventListener("input", ({ target }) => {
    let value = target.value;
    if (!value) return rows.forEach((e) => e.classList.remove("hide"));
    rows.forEach((e) => {
        if (e.dataset.outlet == value) return e.classList.remove("hide");
        e.classList.add("hide");
    });
});
