/*- =========================================================================
File Name: bootstrap-toast.js
Description: This Page contains Icon toast and Progress bars.
--------------------------------------------------------------------------
Item Name: Atlas Painel
Version: 1.0
Author: Atlas
Author URL: https://atlaspainel.shop
==========================================================================*/
// toast initialize
$('.toast-toggler').on('click', function () {
  $(this).next('.toast').prependTo('.toast-bs-container .toast-position').toast('show')
});
// for toast placement
$('.placement').on('click', function () {
  $('.toast-placement .toast').toast('show');
});

