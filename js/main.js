$(window).load(function () {
  // Una vez se cargue al completo la página desaparecerá el div "cargando"
  $('#cargando').hide();

  var mediaquery = window.matchMedia("(min-width: 1024px)");
  if (mediaquery.matches) {
   $('[data-toggle="tooltip"]').tooltip();
 } else {
  // mediaquery no es 600
}

});