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
                return "Are you sure that you want to remove this alias? ("+item.alias+")";
            },
    
            controller: {
                loadData: (filters) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/topics/aliases/?" + $.param(filters)
                        }).done((response) => {
                            response.forEach((el) => {
                                el.topic_name = el.topic.name;
                            });
                            resolve(response);
                        });
                    });
                },

                insertItem: (item) => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/topics/aliases/new/",
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
                            url: AnalyzerGUI.baseUrl + "/api/topics/aliases/modify/",
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
                            url: AnalyzerGUI.baseUrl + "/api/topics/aliases/delete/" + item.id,
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
                { name: "id", type: "number", title: "ID", width: 20 },
                { name: "topic_name", type: "text", title: "Topic", width: 40, editing: false},
                { name: "alias", type: "text", title: "Alias", width: 40},
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