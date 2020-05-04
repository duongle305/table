document.addEventListener("DOMContentLoaded", function () {
    if (!$().DataTable) {
        console.warn("Warning - datatables.min.js is not loaded.")
        return;
    }
    window.DuoLeeDataTables = window.DuoLeeDataTables || {};
    window.DuoLeeDataTables["%1$s"] = $("#%1$s").DataTable("%2$s");
});
