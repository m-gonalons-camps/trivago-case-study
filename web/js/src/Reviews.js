"use strict";

module.exports = class {

    load() {
        $("#jsGrid").jsGrid({
            height: "auto",
            width: "100%",
    
            sorting: true,
            paging: false,
            autoload: true,
    
            controller: {
                loadData: () => {
                    return new Promise((resolve) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/reviews",
                            dataType: "json"
                        }).done(resolve);
                    });
                }
            },
    
            fields: [
                { name: "Review", type: "text" },
                { name: "Total score", type: "text" },
                // TODO
            ]
        });
    };

    showTestAnalyzerModal() {
        $('#testAnalyzerModal').modal();
    };

    testAnalyzeAndRenderResults() {
        $.ajax({
            url: AnalyzerGUI.baseUrl + "/api/reviews/testAnalyzer/",
            method: "POST",
            data: $('#textareaReviewTestAnalyze').val(),
        }).done((response) => {
            $('#json-renderer').jsonViewer(
                response,
                {
                    collapsed: true
                }
            );
        });
    };

};