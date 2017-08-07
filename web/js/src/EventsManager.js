"use strict";

module.exports = class {

    addEventListeners() {
        $('#testAnalyzerButton').click(this.testAnalyzerButtonHasBeenClicked);
        $('#modalTestAnalyzeButton').click(this.testAnalyzeModalButtonHasBeenClicked);
    }

    testAnalyzerButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Reviews.showTestAnalyzerModal(clickEvent);
    }

    testAnalyzeModalButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Reviews.testAnalyzeAndRenderResults(clickEvent);
    }

};