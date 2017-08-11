"use strict";

module.exports = class {

    loadGrid() {
        AnalyzerGUI.Selectors.jsGrid.jsGrid('reset');
        AnalyzerGUI.Selectors.jsGrid.jsGrid({
            height: "auto",
            width: "100%",
    
            filtering: true,
            sorting: true,
            autoload: true,
            editing: true,
            paging: true,
            inserting: true,
    
            controller: {
                loadData: () => {
                    return new Promise((resolve) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/reviews/",
                            dataType: "json"
                        }).done(resolve);
                    });
                }
            },
    
            fields: [
                { name: "id", type: "number", title: "ID", width: 10, editing: false },
                { name: "text", type: "textarea", title: "Review", width: 150 },
                { name: "total_score", title: "Score", type: "text", width: 20, inserting: false, editing: false },
                { name: "detailed_results", title: "Detailed results", width: 50, type: "text", inserting: false, editing: false},
                { title: "Analyze", type: "text", width: 20, inserting: false, editing: false},
                { type: "control", width: 20}
            ]
        });
    };

    showTestAnalyzerModal() {
        AnalyzerGUI.Selectors.testAnalyzerModal.modal();
    };

    testAnalyzeAndRenderResults() {
        AnalyzerGUI.Selectors.jsonRenderer.empty();

        $('<span>Analyzing...</span>').appendTo(AnalyzerGUI.Selectors.jsonRenderer);

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