/**
 * @file
 * Parse inline HAL-JSON representations of Drupal entities for tattoo global array.
 */
(function (_) {
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

  function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
  }

  window.Tattoo = {
    findBySelfHref: function (href) {
      return _.find(window.tattoo, function (entity) {
        var exp = new RegExp('^' + escapeRegExp(href) + '(\\?_format\\=hal_json)?$');
        return exp.test(entity._links.self.href);
      });
    }
  };
})(_);
