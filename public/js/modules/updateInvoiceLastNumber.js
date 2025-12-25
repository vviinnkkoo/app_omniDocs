const refreshBtn = document.getElementById("refresh-number-btn");
if (refreshBtn) {
    refreshBtn.addEventListener("click", function () {
        const numberInput = document.getElementById("number");
        const year = document.getElementById("year").value;
        const loader = document.getElementById("numberLoader");
        const icon = document.getElementById("refresh-icon"); // dodaj id na <i>

        numberInput.disabled = true;
        numberInput.classList.add("opacity-50");
        loader.classList.remove("d-none");
        icon.classList.add("d-none"); // sakrij ikonu

        fetch(`/racuni/zadnji-broj/${year}`)
            .then(response => response.json())
            .then(data => {
                setTimeout(() => {
                    numberInput.value = data.latest;

                    numberInput.disabled = false;
                    numberInput.classList.remove("opacity-50");
                    loader.classList.add("d-none");
                    icon.classList.remove("d-none"); // pokaži ikonu nazad
                }, 500);
            })
            .catch(() => {
                numberInput.disabled = false;
                numberInput.classList.remove("opacity-50");
                loader.classList.add("d-none");
                icon.classList.remove("d-none"); // pokaži ikonu nazad
            });
    });
}
