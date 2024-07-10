!(function () {
    const baseURL = window.location.origin;
    fetch(baseURL + "/dashboard/balance")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            document.getElementById("topBarBalance").innerText = data?.balance || 0;
        })
        .catch((error) => {
            console.error("Error fetching balance:", error);
        });
})();
