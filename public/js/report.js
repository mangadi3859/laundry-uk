const printBtn = document.querySelector("#printBtn");

printBtn.addEventListener("click", () => print());

let params = new URLSearchParams(window.location.search);
let isPrintParam = params.has("print");
params.delete("print");
if (isPrintParam) window.print();
window.history.pushState(null, null, window.location.pathname + "?" + params.toString());
