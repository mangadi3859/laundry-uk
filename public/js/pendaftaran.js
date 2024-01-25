let search = new URLSearchParams(window.location.search);
let err = search.get("err");

search.delete("err");
window.history.pushState(null, null, `${window.location.pathname}?${search.toString()}`);

if (err)
    Swal.fire({
        title: "Data invalid",
        text: err,
        icon: "error",
    });