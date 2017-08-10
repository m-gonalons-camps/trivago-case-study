"use strict";

module.exports = class {

    changeSection(navButtonClicked, sectionClass) {
        $("ul.navbar-nav li.active").removeClass("active");
        navButtonClicked.addClass("active");

        this.navButtonClicked = navButtonClicked;

        this.sectionButtonsUpdate(AnalyzerGUI.Selectors.navReviewsButton, AnalyzerGUI.Selectors.reviewsButtonsDiv);
        this.sectionButtonsUpdate(AnalyzerGUI.Selectors.navTopicsButton, AnalyzerGUI.Selectors.topicsButtonsDiv);

        sectionClass.loadGrid();
    }

    sectionButtonsUpdate(navButton, buttonsDiv) {
        if (this.navButtonClicked.attr('id') === navButton.attr("id")) 
            buttonsDiv.css('display', 'block');
        else
            buttonsDiv.css('display', 'none');
    }

}