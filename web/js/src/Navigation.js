"use strict";

module.exports = class {

    changeSection(navButtonClicked, sectionClass) {
        $("ul.navbar-nav li.active").removeClass("active");
        navButtonClicked.addClass("active");

        this.reviewButtonsUpdate(navButtonClicked);

        sectionClass.loadGrid();
    }

    reviewButtonsUpdate(navButtonClicked) {
        if (navButtonClicked.attr('id') === AnalyzerGUI.Selectors.navReviewsButton.attr("id")) {
            AnalyzerGUI.Selectors.reviewsButtonsDiv.css('display', 'block');
        } else {
            AnalyzerGUI.Selectors.reviewsButtonsDiv.css('display', 'none');
        }
    }

}