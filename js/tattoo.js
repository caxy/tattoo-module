/**
 * @file
 * Parse inline HAL-JSON representations of Drupal entities for tattoo global array.
 */
(function() {
    'use strict';

    // Use direct child elements to harden against XSS exploits when CSP is on.
    var scriptElements = document.querySelectorAll('head > script[type="application/hal+json"][data-drupal-selector="tattoo-entity"]');

    /**
     * Variable generated with all the tattooed entities.
     *
     * @global
     *
     * @type {object}
     */
    window.tattoo = [];

    if (scriptElements !== null) {
        window.tattoo = _.map(scriptElements, function (script) {
            return JSON.parse(script.textContent);
        });
    }
})();
