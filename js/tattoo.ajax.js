/* globals tattoo, _, Drupal */
(function (Drupal, tattoo, _) {
  'use strict';

  Drupal.AjaxCommands.prototype.tattoo = function (ajax, response, status) {
    var entity = response.entity;

    // Find the tattoo index (if any) for the changed entity.
    var index = _.findIndex(tattoo, function (tattooedEntity) {
      return tattooedEntity._links.self.href === entity._links.self.href;
    });

    if (index > -1 && response.delete === true) {
      // Replace the edited entity in tattoo collection.
      tattoo.splice(index, 1);
    }
    else if (index > -1) {
      // Replace the edited entity in tattoo collection.
      tattoo[index] = entity;
    }
    else {
      // Append the new entity to the tattoo collection.
      tattoo.push(entity);
    }
  };
})(Drupal, tattoo, _);
