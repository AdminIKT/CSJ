$(document).ready(function () {
    $("#accountsAccordion .form-control").on("input", function () {
        let query = $(this).val().trim().toLowerCase();

        $("#accountsAccordion .accordion-item").each(function () {
            let title  = $(this).find(".account-name");
            let serial = $(this).find(".card-subtitle");

            let titleTxt  = title.text();
            let serialTxt = serial.text();

            let inTitle = titleTxt.toLowerCase().includes(query);
            let inSerial = serialTxt.toLowerCase().includes(query);

            if (inTitle || inSerial) {
                $(this).show();

                if (inTitle) {
                    title.html(highlightText(titleTxt, query));
                } else {
                    title.html(titleTxt);
                }
                if (inSerial) {
                    serial.html(highlightText(serialTxt, query));
                } else {
                    serial.html(serialTxt);
                }

            } else {
                $(this).hide();
            }
        });

        if (query.length === 0) {
            $("#accountsAccordion .accordion-item").show();
        }
    });

    function highlightText(text, query) {
        let regex = new RegExp(`(${query})`, "gi");
        return text.replace(regex, `<span style="background:lightblue">$1</span>`);
    }
});

