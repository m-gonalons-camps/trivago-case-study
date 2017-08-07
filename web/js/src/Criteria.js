"use strict";

module.exports = class {

    loadGrid() {
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
                loadData: () => {
                    return new Promise((resolve) => {
                        $.ajax({
                            url: AnalyzerGUI.baseUrl + "/api/criteria",
                            dataType: "json"
                        }).done(resolve);
                    });
                }
            },
    
            fields: [
                { name: "id", type: "number", width: 20 },
                { name: "keyword", type: "text", width: 50},
                { name: "score", type: "number", width: 30 },
                { type: "control", width: 20}
            ]
        });
    }
}