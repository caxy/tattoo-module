(function() {
    angular
        .module('tattoo', [])
        .service('TattooService', TattooService)
        .constant('_', _);

    TattooService.$inject = ['$document', '_'];

    function TattooService($document, _) {
        var scripts = $document.find('script[type="application/hal+json"][data-drupal-selector="tattoo-entity"]');

        return _.map(scripts, function (script) {
            return JSON.parse(script.textContent);
        });
    }
})();
