"use strict";

module.exports = class {

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