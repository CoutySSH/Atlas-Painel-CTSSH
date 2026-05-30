/*=========================================================================================
  File Name: form-validation.js
  Description: jquery bootsreap validation js
  ----------------------------------------------------------------------------------------
  Item Name: Atlas Painel
Version: 1.0
Author: Atlas
Author URL: https://atlaspainel.shop
==========================================================================================*/

(function(window, document, $) {
  'use strict';

  // Input, Select, Textarea validations except submit button
  $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();

})(window, document, jQuery);
