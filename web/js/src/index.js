window.AnalyzerGUI = (() => {

    init = (backendParameters) => {
        loadDependencies();

        AnalyzerGUI.baseUrl = "http://" + backendParameters.httpHost;

        AnalyzerGUI.EventsManager = new (require("./EventsManager"))();
        AnalyzerGUI.Reviews = new (require("./Reviews"))();

        AnalyzerGUI.EventsManager.addEventListeners();
        AnalyzerGUI.Reviews.load();
    },

    loadDependencies = () => {
        window.jQuery = window.$ = require("jquery");
        require("../../node_modules/jsgrid/dist/jsgrid.min.css");
        require("../../node_modules/jsgrid/dist/jsgrid-theme.min.css");
        require("../../node_modules/jquery.json-viewer/json-viewer/jquery.json-viewer.css");
        require("bootstrap");
        require("jsgrid");
        require("../../node_modules/jquery.json-viewer/json-viewer/jquery.json-viewer.js");

        require("../../css/index.css");
    };

    return {init: init};

})();