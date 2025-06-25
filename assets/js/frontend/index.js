import $ from 'jquery';

$(document).ready(function () {
   $("#inputFiltrar").keyup(function () {
      console.log('Key Up');
      var rex = new RegExp($(this).val(), 'i');
      if ($(this).val() == '' || $(this).val() == null) {
         console.log("vacío");
         $(".listaServicios").show();
         $(".subfamilias").show();
         $(".btn-azpifamilia").show();
         $(".tituloFamilia-block").show();
         $(".js-children-block").hide();
         $(".js-children-block-close").show();
         $(".js-children-block-close").children().show();
         return;
      }
      $(".subfamilias, .btn-azpifamilia").hide();
      $(".tituloFamilia-block").hide();
      $(".listaServicios").find("*").not(".btn-azpifamilia").filter(function () {
         if (!rex.test($(this).text())) {
            $(this).hide();
         }
         if (rex.test($(this).text())) {
            console.log($(this).attr('id'));
            $(this).show();
            $(this).closest('.espedienteKodeaBtn').show();
            $(this).closest('.js-children-block').children("a").children(".js-lista-servicios").children("button").show();
            $(this).closest('.familia').children().show();
            //$(this).closest('.listaServicios').show();
            // $(this).closest('.listaServicios').children("").show();
         }
      })
   });
});