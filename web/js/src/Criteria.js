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

            deleteConfirm: (item) => {
                return "Are you sure that you want to remove this criteria? ("+item.keyword+")";
            },
    
            controller: {
                loadData: (filters) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/criteria?" + $.param(filters)
                        }).done(resolve);
                    });
                },

                insertItem: (item) => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/criteria/new/",
                            dataType: "json",
                            method: "POST",
                            data: JSON.stringify(item)
                        })
                        .done(() => {
                            alert('Success');
                            resolve();
                        })
                        .fail((obj) => {
                            this.errorHandler(obj.responseText);
                            reject();
                        });
                    });
                },

                updateItem: (item) => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/criteria/modify/",
                            dataType: "json",
                            method: "POST",
                            data: JSON.stringify(item)
                        })
                        .done(() => {
                            alert('Success');
                            resolve();
                        })
                        .fail((obj) => {
                            this.errorHandler(obj.responseText);
                            reject();
                        });
                    });
                },
                
                deleteItem: (item) => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/criteria/delete/" + item.id,
                            method: "DELETE",
                        })
                        .done(() => {
                            alert('Success');
                            resolve();
                        })
                        .fail((obj) => {
                            this.errorHandler(obj.responseText);
                            reject();
                        });
                    });
                }
            },
    
            fields: [
                { name: "id", type: "number", title: "ID", width: 20, editing: false, inserting: false },
                { name: "keyword", type: "text", title: "Keyword", width: 50},
                { name: "score", type: "number", title: "Score", width: 30 },
                { type: "control", width: 20}
            ]
        });
    };

    errorHandler(errorObj) {
        let parsedError;

        try {
            parsedError = JSON.parse(errorObj).error;
        } catch (Exception) {
            parsedError = errorObj;
        }
        alert('An error ocurred: ' + parsedError);
    }
}