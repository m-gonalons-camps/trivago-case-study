"use strict";

module.exports = class {

    loadGrid() {
        AnalyzerGUI.Selectors.jsGrid.jsGrid({
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
        AnalyzerGUI.Selectors.testAnalyzerModal.modal();
    };

    testAnalyzeAndRenderResults() {
        AnalyzerGUI.Selectors.jsonRenderer.empty();

        $('<span>Analyzing...</span>')
            .appendTo(AnalyzerGUI.Selectors.jsonRenderer)
            .css('visibility', 'visible');

        $.ajax({
            url: AnalyzerGUI.baseUrl + "/api/reviews/testAnalyzer/",
            method: "POST",
            data: AnalyzerGUI.Selectors.textareaReviewTestAnalyze.val(),
        }).done((response) => {
            let totalScore = 0;
            
            for (var el in response)
                totalScore += response[el].score;

            AnalyzerGUI.Selectors.jsonRenderer.jsonViewer({
                totalScore: totalScore,
                detailedResults: response
            });
        });
    };

};