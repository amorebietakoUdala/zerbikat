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

function aplicarColoresAlternos(selector) {
   let count = 0;
   $(selector).filter(":visible").each(function() {
      count = 0;
      const $el = $(this);
      const $children = $el.find('.fitxa-lerroa');
      const visibles = $children.filter(':visible');
      if (visibles.length >= 0) {
         $(visibles).each(function(){
            count++;
            const $el = $(this);
            console.log($el);
            $el.removeClass('fitxa-lerroa-par-bg fitxa-lerroa-impar-bg');
            if (count % 2 === 0) {
               $el.addClass('fitxa-lerroa-par-bg');
            } else {
               $el.addClass('fitxa-lerroa-impar-bg');
            }
         });
         console.log(count);
      }
   });
}

function ocultarSiTodosLosHijosEstanOcultos(selector) {
   $(selector).each(function () {
      const $parent = $(this);
      const $children = $parent.children('.listaServicios').children('.js-children-block');
      const visibles = $children.filter(':visible');
      if (visibles.length === 0) {
         $parent.hide();
         $parent.find('.js-children-block-close').hide();
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

      // Ocultar los botones y flechas de volver al buscar
      $(".btn-azpifamilia, .js-children-block").hide();
      // Recuperar las familias que se habían ocultado en un keyup anterior, sino no se muestran los resultados de esa familia aunque se filtren correctamente.
      $(".familia").show();
      let count = 0;

      $(".js-lista-servicios").each(function () {
         let $el = $(this);
         let text = $el.text();
         if (rex.test(text)) {
            console.log(text + $el.attr('id'));
            $el.show();
            $el.children().show();
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
      aplicarColoresAlternos('.familia');
      console.log("Total Matching: " + count);
   });
});