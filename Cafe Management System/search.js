document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchAvailable");
    const rows = Array.from(document.querySelectorAll("table tr"));

    searchInput.addEventListener("input", function() {
        const searchTerm = searchInput.value.toLowerCase();

        rows.forEach(function(row, index) {
            if (index !== 0) {
                const cells = Array.from(row.querySelectorAll("td"));
                const foundMatch = cells.some(function(cell) {
                    return cell.textContent.toLowerCase().includes(searchTerm);
                });

                if (foundMatch) {
                    row.style.display = "table-row";
                } else {
                    row.style.display = "none";
                }
            }
        });
    });
});
