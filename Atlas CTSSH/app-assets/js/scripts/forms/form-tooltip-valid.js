/*=========================================================================================
    File Name: form-tooltip-valid.js
    Description: form tooltip validation etc..
    ----------------------------------------------------------------------------------------
    Item Name: Atlas Painel
    Version: 1.0
    Author: Atlas
    Author URL: https://atlaspainel.shop
==========================================================================================*/
(function(window, document, $) {
  'use strict';

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  // Loop over them and prevent submission
  $("button").click(function () {
    var form = $(".needs-validation");
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
    }
    form.addClass('was-validated');
  });

})(window, document, jQuery);
