window.AnalyzerGUI = (() => {

    init = (backendParameters) => {
        loadDependencies();

        AnalyzerGUI.baseUrl = "http://" + backendParameters.httpHost;

        AnalyzerGUI.Selectors = require("./Selectors");
        AnalyzerGUI.EventsManager = new (require("./EventsManager"))();
        AnalyzerGUI.Navigation = new (require("./Navigation"))();
        AnalyzerGUI.Reviews = new (require("./Reviews"))();
        AnalyzerGUI.Topics = new (require("./Topics"))();
        AnalyzerGUI.Criteria = new (require("./Criteria"))();
        AnalyzerGUI.Emphasizers = new (require("./Emphasizers"))();

        AnalyzerGUI.EventsManager.addEventListeners();
        AnalyzerGUI.Reviews.loadGrid();
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