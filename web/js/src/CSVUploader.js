"use strict";

module.exports = class {

    uploadFile(clickEvent) {
        clickEvent.preventDefault();
        clickEvent.stopPropagation();
        if (! this.validations()) return;

        AnalyzerGUI.Selectors.csvUploadProgressBar.css('visibility', 'visible');
        AnalyzerGUI.Selectors.csvUploadProgressBar.attr({
            value: 0,
            max: 0,
        });

        $.ajax({
            url: AnalyzerGUI.baseUrl + "/api/reviews/upload/",
            type: "POST",
            data: new FormData($('#uploadCSVForm')[0]),
            cache: false,
            contentType: false,
            processData: false,
            
            xhr: () => {
                const myXhr = $.ajaxSettings.xhr();
                if (! myXhr.upload) return myXhr;

                myXhr.upload.addEventListener('progress', (event) => {
                    if (! event.lengthComputable) return null;
                    AnalyzerGUI.Selectors.csvUploadProgressBar.attr({
                        value: event.loaded,
                        max: event.total,
                    });
                } , false);

                return myXhr;
            }
        })
        .done((response) => {
            alert('Success');
            AnalyzerGUI.Selectors.modalUploadCSVFile.modal('hide');
            AnalyzerGUI.Selectors.jsGrid.jsGrid("loadData");
        })
        .always((response) => {
            console.log(response);
            AnalyzerGUI.Selectors.csvUploadProgressBar.css('visibility', 'hidden');
        });
    }

    
    validations() {
        // must have .csv extension

        return true;
    }

}