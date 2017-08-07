"use strict";

module.exports = class {

    addEventListeners() {
        AnalyzerGUI.Selectors.navReviewsButton.click(this.navReviewsButtonHasBeenClicked);
        AnalyzerGUI.Selectors.navTopicsButton.click(this.navTopicsButtonHasBeenClicked);
        AnalyzerGUI.Selectors.navCriteriaButton.click(this.navCriteriaButtonHasBeenClicked);
        AnalyzerGUI.Selectors.navEmphasizersButton.click(this.navEmphasizersButtonHasBeenClicked);

        AnalyzerGUI.Selectors.testAnalyzerButton.click(this.testAnalyzerButtonHasBeenClicked);
        AnalyzerGUI.Selectors.modalTestAnalyzeButton.click(this.testAnalyzeModalButtonHasBeenClicked);
    }

    navReviewsButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.changeSection(
            $(clickEvent.currentTarget),
            AnalyzerGUI.Reviews
        );
    }

    navTopicsButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.changeSection(
            $(clickEvent.currentTarget),
            AnalyzerGUI.Topics
        );
    }

    navCriteriaButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.changeSection(
            $(clickEvent.currentTarget),
            AnalyzerGUI.Criteria
        );
    }

    navEmphasizersButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Navigation.changeSection(
            $(clickEvent.currentTarget),
            AnalyzerGUI.Emphasizers
        );
    }

    testAnalyzerButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Reviews.showTestAnalyzerModal(clickEvent);
    }

    testAnalyzeModalButtonHasBeenClicked(clickEvent) {
        AnalyzerGUI.Reviews.testAnalyzeAndRenderResults(clickEvent);
    }

};