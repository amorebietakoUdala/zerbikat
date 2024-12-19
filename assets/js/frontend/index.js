import $ from 'jquery';

$(document).ready(function () {
   $("#filtrar").keyup(function () {
      console.log('Key Up');
      var rex = new RegExp($(this).val(), 'i');
      /* En IE -> cuando no hay valor en el filtro detecta ''. En firefox y chrome detecta (?:).*/
      if (rex.source == '' || rex.source == '(?:)') {
         $(".servicio").show();
         $(".servicio").children("h2").show();
         $(".servicio").children(".subtitulo").show();
         $(".servicio").children("h2").removeClass("cerrar");
         $(".listaServicios").css("display", "none");
      } else {
         $(".servicio").hide();
         $(".servicio").children("h2").hide();
         $(".servicio").children(".subtitulo").hide();
         $(".listaServicios").find("*").filter(function () {
            if (!rex.test($(this).text())) {
               $(this).hide();
            }
            if (rex.test($(this).text())) {
               $(this).closest(".servicio").show();
               $(this).closest(".servicio").children("h2").show();
               $(this).closest(".servicio").children(".subtitulo").show();
               $(this).closest(".servicio").children(".listaServicios").show();
               $(this).show();
            }
         })
         $(".listaServiciosDesplegado").find("*").filter(function () {
            if (!rex.test($(this).text())) {
               $(this).hide();
            }
            if (rex.test($(this).text())) {
               $(this).closest(".servicio").show();
               $(this).closest(".servicio").children("h2").show();
               $(this).closest(".servicio").children(".subtitulo").show();
               $(this).closest(".servicio").children(".listaServiciosDesplegado").show();
               $(this).show();
            }
         })
         $(".servicio").children("h2").addClass("cerrar");
      }
   })
});