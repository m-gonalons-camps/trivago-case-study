"use strict";

module.exports = class {

    loadGrid() {
        this.allowDecimalFieldTypes();
        
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
                return "Are you sure that you want to remove this emphasizer? ("+item.name+")";
            },
    
            controller: {
                loadData: (filters) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/emphasizers?" + $.param(filters)
                        }).done(resolve);
                    });
                },

                insertItem: (item) => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/emphasizers/new/",
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
                            url: AnalyzerGUI.baseUrl + "/api/emphasizers/modify/",
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
                            url: AnalyzerGUI.baseUrl + "/api/emphasizers/delete/" + item.id,
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
                { name: "id", type: "number",title: "ID", width: 20, editing: false, inserting: false },
                { name: "name", type: "text", title: "Name", width: 50},
                { name: "score_modifier", type: "decimal", title: "Score modifier", width: 30},
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
    };

    allowDecimalFieldTypes() {
        function DecimalField(config) {
            jsGrid.fields.number.call(this, config);
        }

        DecimalField.prototype = new jsGrid.fields.number({

            filterValue: function() {
                return this.filterControl.val()
                    ? parseFloat(this.filterControl.val() || 0, 10)
                    : undefined;
            },

            insertValue: function() {
                return this.insertControl.val()
                    ? parseFloat(this.insertControl.val() || 0, 10)
                    : undefined;
            },

            editValue: function() {
                return this.editControl.val()
                    ? parseFloat(this.editControl.val() || 0, 10)
                    : undefined;
            }
        });

        jsGrid.fields.decimal = jsGrid.DecimalField = DecimalField;
    }
}