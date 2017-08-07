window.AnalyzerGUI = (() => {

    let EventsManager,
        baseUrl,

    init = (backendParameters) => {
        loadDependencies();

        baseUrl = 'http://' + backendParameters.httpHost;

        EventsManager = new (require("./EventsManager"))();
        EventsManager.addEventListeners();

        loadReviews();
    },

    loadDependencies = () => {
        window.jQuery = window.$ = require('jquery');
        require('jsgrid');
    },
    
    loadReviews = () => {
        $("#jsGrid").jsGrid({
            height: "auto",
            width: "100%",
    
            sorting: true,
            paging: false,
            autoload: true,
    
            controller: {
                loadData: function() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: baseUrl + "/api/reviews",
                            dataType: "json"
                        }).done(function(response) {
                            console.log(response);
                            resolve(response.value);
                        });
                    });
                }
            },
    
            fields: [
                { name: "Review", type: "text" },
                { name: "Total score", type: "text" },
                // TODO
            ]
        });
    };

    return {
        init: init
    };

})();