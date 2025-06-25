import $ from 'jquery';

$(document).ready(function () {
   //$(".js-familia-children-block").hide();
   $("#inputFiltrar").keyup(function () {
      console.log('Key Up');
      var rex = new RegExp($(this).val(), 'i');
      if ($(this).val() == '' || $(this).val() == null) {
         console.log("vacío");
         $(".listaServicios").show();
         $(".subfamilias").show();
         $(".btn-azpifamilia").show();
         return;
      }
      /* En IE -> cuando no hay valor en el filtro detecta ''. En firefox y chrome detecta (?:).*/
      if ( (rex.source == '' || rex.source == '(?:)') ) {
         console.log('No hay valor en el filtro');
         $(".subfamilias").show();
         $(".subfamilias").children("h2").show();
         $(".listaServicios").hide();
      } else {
         $(".subfamilias").hide();
         $(".subfamilias").children("h2").hide();
         $(".listaServicios").find("*").filter(function () {
            if (!rex.test($(this).text())) {
               $(this).hide();
            }
            if (rex.test($(this).text())) {
               $(this).closest(".subfamilias").show();
               $(this).closest(".subfamilias").children("h2").show();
               $(this).closest(".subfamilias").children(".listaServicios").show();
               $(this).show();
            }
         })
         $(".listaServicios").find("*").filter(function () {
            if (!rex.test($(this).text())) {
               $(this).hide();
            }
            if (rex.test($(this).text())) {
               $(this).closest(".subfamilias").show();
               $(this).closest(".subfamilias").children("h2").show();
               $(this).closest(".subfamilias").children(".listaServicios").show();
               $(this).show();
            }
         })
      }
   })
});