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
                return "Are you sure that you want to remove this topic? ("+item.name+")";
            },
    
            controller: {
                loadData: (filters) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/topics?" + $.param(filters)
                        }).done((response) => {
                            response.forEach((el) => {
                                let aliases = [];
                                el.aliases.forEach((el) => {
                                    aliases.push(el.alias);
                                });
                                el.alias = aliases.join(', ');
                            });
                            resolve(response);
                        });
                    });
                },

                insertItem: (item) => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/topics/new/",
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
                            url: AnalyzerGUI.baseUrl + "/api/topics/modify/",
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
                            url: AnalyzerGUI.baseUrl + "/api/topics/delete/" + item.id,
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
                { name: "name", type: "text", title: "Name", width: 30},
                { name: "alias", type: "text", title: "Aliases", width: 60, inserting: false, editing: false },
                { name: "priority", type: "number", title: "Priority", width: 10 },
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