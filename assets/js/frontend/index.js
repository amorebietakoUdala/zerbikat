import $ from 'jquery';

function aplicarColoresIniciales(selector) {
   $(selector).each(function() {
      const $el = $(this);
      if ($el.data('loop-index') % 2 === 0) {
         $el.addClass('fitxa-lerroa-par-bg');
      } else {
         $el.addClass('fitxa-lerroa-impar-bg');
      }
   });
}

function reset() {
   $(".listaServicios").show();
   $(".btn-azpifamilia").show();
   $(".tituloFamilia-block").show();
   $(".js-children-block").hide();
   $(".js-children-block-close").show();
   $(".js-children-block-close").children().show();
   $(".subfamilias").show();
   $(".familia").show();
   $(".subfamilias").children().show();
   $(".js-lista-servicios").show();
   aplicarColoresIniciales('.fitxa-lerroa');
}

function ocultarSiTodosLosHijosEstanOcultos(selector) {
   $(selector).each(function () {
      const $parent = $(this);
      const $children = $parent.children('.listaServicios').children('.js-children-block');
      const visibles = $children.filter(':visible');
      if (visibles.length === 0) {
         $parent.hide();
      }
   });
}

$(document).ready(function () {
   $("#inputFiltrar").keyup(function () {
      const filtro = $(this).val();
      if (filtro === '' || filtro === null) {
         console.log("vacío");
         reset();
      }
      // No buscar hasta tener 3 caracteres
      if (filtro.length < 3) {
         return;
      }

      const rex = new RegExp($(this).val(), 'i');

      $(".btn-azpifamilia").hide();
      let count = 0;

      $(".js-lista-servicios").each(function () {
         let $el = $(this);
         let text = $el.text();
         if (rex.test(text)) {
            console.log(text + $el.attr('id'));
            $el.children().show();
            $el.show();
            $el.parent().show();
            $el.parent().parent().show();
            $el.parent().parent().children('.js-children-block-close').hide();
            $el.parent().parent().parent().children('.subfamilias').hide();
            count++;
         } else {
            $el.hide();
         }
      });
      ocultarSiTodosLosHijosEstanOcultos('.familia');
      console.log("Total Matching: " + count);
   });
});