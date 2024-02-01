const printBtn = document.querySelector("#printBtn");

printBtn.addEventListener("click", () => print());

let isPrintParam = new URLSearchParams(window.location.search).has("print");
if (isPrintParam) window.print();
window.history.pushState(null, null, window.location.pathname);
