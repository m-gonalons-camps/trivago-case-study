"use strict";

module.exports = class {

    addEventListeners() {
        AnalyzerGUI.Selectors.navReviewsButton.click(this.navReviewsButtonHasBeenClicked);
        AnalyzerGUI.Selectors.navTopicsButton.click(this.navTopicsButtonHasBeenClicked);
        AnalyzerGUI.Selectors.navCriteriaButton.click(this.navCriteriaButtonHasBeenClicked);
        AnalyzerGUI.Selectors.navEmphasizersButton.click(this.navEmphasizersButtonHasBeenClicked);
        AnalyzerGUI.Selectors.switchToAliasesButton.click(this.switchToTopicAliasesButtonHasBeenClicked);

        AnalyzerGUI.Selectors.testAnalyzerButton.click(this.testAnalyzerButtonHasBeenClicked);
        AnalyzerGUI.Selectors.modalTestAnalyzeButton.click(this.testAnalyzeModalButtonHasBeenClicked);

        AnalyzerGUI.Selectors.uploadCSVFileButton.click(this.uploadCSVReviewsButtoHasBeenClicked);
        AnalyzerGUI.Selectors.analyzeAllReviewsButton.click(this.analyzeAllReviewsButtonHasBeenClicked);
        AnalyzerGUI.Selectors.modalUploadCSVButton.click(this.formUploadCSVButtonHasBeenClicked);
    }

    navReviewsButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.changeSection(
            $(clickEvent.currentTarget),
            AnalyzerGUI.GridConfig.Reviews
        );
    }

    navTopicsButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.changeSection(
            $(clickEvent.currentTarget),
            AnalyzerGUI.GridConfig.Topics
        );
    }

    navCriteriaButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.changeSection(
            $(clickEvent.currentTarget),
            AnalyzerGUI.GridConfig.Criteria
        );
    }

    navEmphasizersButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.changeSection(
            $(clickEvent.currentTarget),
            AnalyzerGUI.GridConfig.Emphasizers
        );
    }

    switchToTopicAliasesButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.loadGrid(AnalyzerGUI.GridConfig.TopicsAliases);
    }

    testAnalyzerButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.ReviewsAnalyzer.showTestAnalyzerModal(clickEvent);
    }

    testAnalyzeModalButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.ReviewsAnalyzer.testAnalyzeAndRenderResults(clickEvent);
    }

    uploadCSVReviewsButtoHasBeenClicked(clickEvent) {
        AnalyzerGUI.Selectors.modalUploadCSVFile.modal();
    }

    analyzeAllReviewsButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.ReviewsAnalyzer.analyzeALL(clickEvent);
    }

    formUploadCSVButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.CSVUploader.uploadFile(clickEvent);
    }

};