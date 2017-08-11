"use strict";

module.exports = {


    Reviews: {
        api: 'reviews',

        afterLoadHandler: (serverResponse) => {},

        deleteConfirm: (item) => {
            return "Are you sure that you want to remove the review with the ID " + item.id + "?";
        },

        fields: [{
            name: "id",
            type: "number",
            title: "ID",
            width: 10,
            editing: false 
        },{
            name: "text",
            type: "textarea",
            title: "Review",
            width: 150 
        },{
            name: "total_score",
            title: "Score",
            type: "text",
            width: 20,
            inserting: false,
            editing: false
        },{
            name: "detailed_results",
            title: "Detailed results",
            width: 50,
            type: "text",
            inserting: false,
            editing: false,
            cellRenderer: (value, item) => {
                // Create TD with jQuery
                // Add click event
                // Popup a modal and show the followig info in a table maybe
                // Room: very clean, not comfortable, bad | score: -50
                // Hotel: great, going to come back, | score : 200
                // Staff: friendly | score: 100
                return '<td width="50px">' + JSON.stringify(item.analysis) + '</td>'
            }
        },{
            title: "Analyze",
            type: "text",
            width: 20,
            inserting: false,
            editing: false
        },{
            type: "control",
            width: 20
        }]
    },



    Topics: {
        api: 'topics',

        afterLoadHandler: (serverResponse) => {
            serverResponse.forEach((element) => {
                let aliases = [];
                element.aliases.forEach((element) => {
                    aliases.push(element.alias);
                });
                element.alias = aliases.join(', ');
            });
        },

        deleteConfirm: (item) => {
            return "Are you sure that you want to remove this topic? ("+item.name+")";
        },

        fields: [{
            name: "id",
            type: "number",
            title: "ID",
            width: 20,
            editing: false,
            inserting: false
        },{
            name: "name",
            type: "text",
            title: "Name",
            width: 30
        },{
            name: "alias",
            type: "text",
            title: "Aliases",
            width: 60,
            inserting: false,
            editing: false
        },{
            name: "priority",
            type: "number",
            title: "Priority",
            width: 10
        },{
            type: "control",
            width: 20
        }]
    },



    TopicsAliases: {
        api: 'topics/aliases',

        afterLoadHandler: (serverResponse) => {
            serverResponse.forEach((element) => {
                element.topic_name = element.topic.name;
            });
        },

        deleteConfirm: (item) => {
            return "Are you sure that you want to remove this alias? ("+item.alias+")";
        },

        fields: [{
            name: "id",
            type: "number",
            title: "ID",
            width: 20,
            editing: false,
            inserting: false
        },{
            name: "topic_name",
            type: "text",
            title: "Topic",
            width: 40,
            editing: false
        },{
            name: "alias",
            type: "text",
            title: "Alias",
            width: 40
        },{
            type: "control",
            width: 20
        }]
    },



    Criteria: {
        api: 'criteria',

        afterLoadHandler: (serverResponse) => {},

        deleteConfirm: (item) => {
            return "Are you sure that you want to remove this criteria? (" + item.keyword + ")";
        },

        fields: [{
            name: "id",
            type: "number",
            title: "ID",
            width: 20,
            editing: false,
            inserting: false
        },{
            name: "keyword",
            type: "text",
            title: "Keyword",
            width: 50
        },{
            name: "score",
            type: "number",
            title: "Score",
            width: 30
        },{
            type: "control",
            width: 20
        }]
    },



    Emphasizers: {
        api: 'emphasizers',

        afterLoadHandler: (serverResponse) => {},

        deleteConfirm: (item) => {
            return "Are you sure that you want to remove this emphasizer? ("+item.name+")";
        },
    
        fields: [{
            name: "id",
            type: "number",
            title: "ID",
            width: 20,
            editing: false,
            inserting: false
        },{
            name: "name",
            type: "text",
            title: "Name",
            width: 50
        },{
            name: "score_modifier",
            type: "decimal",
            title: "Score modifier",
            width: 30
        },{
            type: "control",
            width: 20
        }]
    },


    allowDecimalFieldTypes: () => {
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