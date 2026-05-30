/*=========================================================================================
    File Name: Form-Repeater.js
    Description: form repeater page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Atlas Painel
    Version: 1.0
    Author: Atlas
    Author URL: https://atlaspainel.shop
==========================================================================================*/

$(document).ready(function () {
  // form repeater jquery
  $('.file-repeater, .contact-repeater, .repeater-default').repeater({
    show: function () {
      $(this).slideDown();
    },
    hide: function (deleteElement) {
      if (confirm('Are you sure you want to delete this element?')) {
        $(this).slideUp(deleteElement);
      }
    }
  });

});
