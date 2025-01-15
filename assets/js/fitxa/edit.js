import '../../css/fitxa/edit.css';

import $ from 'jquery';

import bootbox from 'bootbox';
import '../common/select2.js';

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

function funcEzabatu(nirediv) {
   console.log(nirediv);
   $(nirediv).remove();
}

function addKostuaFormDeleteLink($tagFormLi) {
   var $removeFormA = $('<div class="col-sm-1"><a href="#"><i class="fa fa-times text-danger"></i></a></div>');
   $tagFormLi.append($removeFormA);

   $removeFormA.on('click', function (e) {
      // prevent the link from creating a "#" on the URL
      e.preventDefault();

      // remove the li for the tag form
      $tagFormLi.remove();
   });
}

function addAraudiaFormDeleteLink($tagFormLi) {
   var $removeFormA = $('<div class="col-sm-1"><a href="#"><i class="fa fa-times text-danger"></i></a></div>');
   $tagFormLi.append($removeFormA);

   $removeFormA.on('click', function (e) {
      // prevent the link from creating a "#" on the URL
      e.preventDefault();

      // remove the li for the tag form
      $tagFormLi.remove();
   });
}

function addProzeduraFormDeleteLink($tagFormLi) {
   var $removeFormA = $('<div class="col-sm-1"><a href="#"><i class="fa fa-times text-danger"></i></a></div>');
   $tagFormLi.append($removeFormA);

   $removeFormA.on('click', function (e) {
      // prevent the link from creating a "#" on the URL
      e.preventDefault();

      // remove the li for the tag form
      $tagFormLi.remove();
   });
}

$(function () {
   Routing.setRoutingData(routes);
   $("#btnFitxaGrabatu").on("click", function () {

      var akatsa = 0;
      var expedientes = $("#fitxa_expedientes").val();
      var parametroa = $("#fitxa_parametroa").val();

      if (parametroa.length > 0) {
         if (parametroa.substring(0, 2) === "08") {
            if (expedientes.length > 0) {
               $("#frm_fitxa_edit").submit();
               return;
            } else {
               alert("Fitxaren Espediente kodea zehaztea beharrezkoa da.");
               return
            }
         }
      }


      $(".kostutaula").each(function () {
         if ($(this).val() === "-1") {
            alert("Kostu taula huts bat gehitu duzu. Aukeratu kostu taula ala ezabatu gehitutako kostu taula.");
            akatsa = 1;
         }
      });

      $(".araudiasel").each(function () {
         if ($(this).val().length === 0) {
            alert("Araudi huts bat gehitu duzu. Aukeratu araudi bat ala ezabatu.");
            akatsa = 1;
         }
      });

      $(".prozedurasel").each(function () {
         if ($(this).val().length === 0) {
            alert("Prozedura huts bat gehitu duzu. Aukeratu prozedura bat ala ezabatu.");
            akatsa = 1;
         }
      });

      if (akatsa === 0) {
         $("#frm_fitxa_edit").submit();
      }

   });

   $("#fitxa_expedientes").on("focus", function () {
      var parametroa = $("#fitxa_parametroa").val();
      if (parametroa.substring(0, 2) === "08") {
         if ($(this).val().length === 0) {
            $(this).val($("#fitxa_espedientekodea").val());
         }
      }
   });

   $(".kostutaula").each(function () {
      console.log($(this).val());
   });

   /**
    * Goazen konboak kargatzera
    */

   var param = $('#url_api_zzoo').val();
   var udalaid = $('#udala_id').val();
   var udalakodea = $('#udala_kodea').val();

   var url = param + "/ordenantzakbykodea/" + udalakodea + "?format=json";
   var locale = global.locale;

   var jqxhr = $.getJSON(url, function (result) {
      if (locale === "es") {
         $("#cmbOrdenantza").append($("<option></option>").attr("value", -1).text("Selecciona"));
      } else {
         $("#cmbOrdenantza").append($("<option></option>").attr("value", -1).text("Aukeratu"));
      }
      $.each(result, function (i, field) {
         if (locale === "es") {
            $("#cmbOrdenantza").append($("<option></option>").attr("value", field.id).text(field.izenburuaes_prod));
         } else {
            $("#cmbOrdenantza").append($("<option></option>").attr("value", field.id).text(field.izenburuaeu_prod));
         }
      });
   });

   let options = {
      language: locale,
      theme: "bootstrap",
   };
   jqxhr.complete(function () {
      $("#cmbOrdenantza").select2(options);
   });


   $('#cmbOrdenantza').on('change', function () {
      console.log("ordenantza change")
      $('#nireloader3').addClass('loading');
      var ordenantzaid = $(this).val();
      var url = param + "/tributuak/" + ordenantzaid + "?format=json";

      var jqxhr = $.getJSON(url, function (result) {
         $("#cmbAtala").empty();
         $("#cmbKontzeptua").empty();
         if (locale === "es") {
            $("#cmbAtala").append($("<option></option>").attr("value", -1).text("Selecciona"));
         } else {
            $("#cmbAtala").append($("<option></option>").attr("value", -1).text("Aukeratu"));
         }

         if (result.length === 0) {
            return;
         }
         var atalak = result;

         $.each(atalak, function (i, field) {
            if (('izenburuaes_prod' in field) || ('izenburuaes_prod' in field))
               if (locale === "es") {
                  var txt = "";
                  if (field.izenburuaes_prod.replace('<br>', '').replace('</br>', '').length > 200) {
                     txt = field.izenburuaes_prod.replace('<br>', '').replace('</br>', '').substr(0, 200);
                  } else {
                     txt = field.izenburuaes_prod;
                  }
                  $("#cmbAtala").append($("<option></option>").attr("value", field.id).text(txt));
               } else {
                  var txt = "";
                  if (field.izenburuaes_prod.replace('<br>', '').replace('</br>', '').length > 200) {
                     txt = field.izenburuaeu_prod.replace('<br>', '').replace('</br>', '').substr(0, 200);
                  } else {
                     txt = field.izenburuaeu_prod;
                  }
                  $("#cmbAtala").append($("<option></option>").attr("value", field.id).text(txt));
               }

            // bete zergen cmb

            if (locale === "es") {
               $("#cmbKontzeptua").append($("<option></option>").attr("value", -1).text("Selecciona"));
            } else {
               $("#cmbKontzeptua").append($("<option></option>").attr("value", -1).text("Aukeratu"));
            }
            var azpiatalak = field.azpiatalak;
            $.each(azpiatalak, function (i, azpi) {


               if (locale === "es") {
                  var txt = "";
                  if (azpi.izenburuaes_prod.replace('<br>', '').replace('</br>', '').length > 200) {
                     txt = azpi.izenburuaes_prod.replace('<br>', '').replace('</br>', '').substr(0, 200);
                  } else {
                     txt = azpi.izenburuaes_prod;
                  }
                  $("#cmbKontzeptua").append($("<option></option>").attr("value", azpi.id).text(txt));
               } else {
                  var txt = "";
                  if (azpi.izenburuaeu_prod.replace('<br>', '').replace('</br>', '').length > 200) {
                     txt = azpi.izenburuaeu_prod.replace('<br>', '').replace('</br>', '').substr(0, 200);
                  } else {
                     txt = azpi.izenburuaeu_prod;
                  }
                  $("#cmbKontzeptua").append($("<option></option>").attr("value", azpi.id).text(txt));
               }


            });
         });
      });

      jqxhr.complete(function () {
         $("#cmbAtala").select2(options);
         $("#cmbKontzeptua").select2(options);
         $('#nireloader3').removeClass('loading');
      });

   });

   $('#cmbAtala').on('change', function () {
      console.log("atala change")
      $('#nireloader3').addClass('loading');
      var atalaid = $(this).val();
      var url = param + "/zergak/" + atalaid + "?format=json";

      var jqxhr = $.getJSON(url, function (result2) {
         $("#cmbKontzeptua").empty();
         if (locale === "es") {
            $("#cmbKontzeptua").append($("<option></option>").attr("value", -1).text("Selecciona"));
         } else {
            $("#cmbKontzeptua").append($("<option></option>").attr("value", -1).text("Aukeratu"));
         }

         if (result2.length === 0) {
            return;
         }

         var azpiatalak = result2;

         $.each(azpiatalak, function (i, field2) {

            if (locale === "es") {
               var txt = "";
               if (field2.izenburuaes_prod.replace('<br>', '').replace('</br>', '').length > 200) {
                  txt = field2.izenburuaes_prod.replace('<br>', '').replace('</br>', '').substr(0, 200);
               } else {
                  txt = field2.izenburuaes_prod;
               }
               $("#cmbKontzeptua").append($("<option></option>").attr("value", field2.id).text(txt));
            } else {
               var txt = "";
               if (field2.izenburuaeu_prod.replace('<br>', '').replace('</br>', '').length > 200) {
                  txt = field2.izenburuaeu_prod.replace('<br>', '').replace('</br>', '').substr(0, 200);
               } else {
                  txt = field2.izenburuaeu_prod;
               }
               $("#cmbKontzeptua").append($("<option></option>").attr("value", field2.id).text(txt));
            }
         });
      });

      jqxhr.complete(function () {
         $("#cmbKontzeptua").select2(options);
         $('#nireloader3').removeClass('loading');
      });

   });

   $('#cmbKontzeptua').on('change', function () {
      console.log("kontzeptua change")
      var kontzeptuaid = $("#cmbKontzeptua").val();

      if (kontzeptuaid === -1) {
         return;
      } else {
         var midest = $('#dest').val();
         $('#' + midest).val(kontzeptuaid).trigger('change');
         console.log("vbalioa da : " + kontzeptuaid);
         $('#modalSelectOrdenantza').modal('toggle')
      }
   });

   $('#saveButton').on('click', function () {
      var kontzeptuaid = $(this).val();

      if (kontzeptuaid === -1) {
         return;
      } else {
         $('#saveButton').removeClass("disabled");
      }
   });


   var shiftWindow = function () {
      scrollBy(0, -75)
   };
   if (location.hash) shiftWindow();
   window.addEventListener("hashchange", shiftWindow);


   var customSorter = function (data) {

      return data.sort(function (a, b) {
         a = a.text.toLowerCase();
         b = b.text.toLowerCase();
         if (a > b) {
            return 1;
         } else if (a < b) {
            return -1;
         }
         return 0;
      });

   };

   let tagsCustomSorterSettings = {
      ...options, ...{
         tags: true,
         sorter: customSorter
      }
   };

   $('#fitxa_etiketak').select2(tagsCustomSorterSettings);
   $('#fitxa_familiak').select2(tagsCustomSorterSettings);
   $('#fitxa_kanalak').select2(tagsCustomSorterSettings);
   $('#fitxa_doklagunak').select2(tagsCustomSorterSettings);
   $('#fitxa_besteak1ak').select2(tagsCustomSorterSettings);
   $('#fitxa_besteak2ak').select2(tagsCustomSorterSettings);
   $('#fitxa_besteak3ak').select2(tagsCustomSorterSettings);
   $('#fitxa_azpiatalak').select2(tagsCustomSorterSettings);
   $('#fitxa_araudiak').select2(tagsCustomSorterSettings);
   $('#fitxa_prozedurak').select2(tagsCustomSorterSettings);
   $('#fitxa_norkeskatuak').select2(tagsCustomSorterSettings);
   let multipleSettings = {
      ...tagsCustomSorterSettings, ...{
         multiple: true,
      }
   };
   $('#fitxa_dokumentazioak').select2(multipleSettings);

   let customSorterSettings = {
      ...options, ...{
         sorter: customSorter
      }
   };
   $('.nireselect2').select2(customSorterSettings);


   $('#cmbServiciosDisponibles').change(function () {

      var aukera = $(this).val();

      if (aukera == "01") {
         $('#cmbParametros').empty();
         $('#cmbParametros').append($("<option></option>").attr("value", "01").text("01-Creación de un registro de entrada"));
      } else if (aukera == "05") {
         $('#cmbParametros').empty();
         $('#cmbParametros').append($("<option></option>").attr("value", "01").text("01-Listado de personas de la vivienda"));
         $('#cmbParametros').append($("<option></option>").attr("value", "02").text("02-Historial de empadronamiento"));
         $('#cmbParametros').append($("<option></option>").attr("value", "03").text("03-Certificado de empadronamiento"));
      } else if (aukera == "08") {
         $('#cmbParametros').empty();
         $('#cmbParametros').append($("<option></option>").attr("value", "01").text("01-Página de ayuda de este tipo de expediente"));
         $('#cmbParametros').append($("<option></option>").attr("value", "02").text("02-Manual de procedicimiento"));
         $('#cmbParametros').append($("<option></option>").attr("value", "03").text("03-Instancia"));
         $('#cmbParametros').append($("<option></option>").attr("value", "04").text("04-Inicio de expediente"));
      } else {
         $('#cmbParametros').empty();
      }
   });

   $('#cmbFamilia').change(function () {
      $('#nireloader2').addClass('loading');
      $('#badago').hide();
      var aukera = $(this).val();
      var fitxaid = $('#fitxa_id').val();
      var urlOrdena = global.base + Routing.generate('api_fitxafamilianextorden', {
         _locale: global.locale,
         fitxa_id: fitxaid,
         familia_id: aukera
      });


      $('#cmbAzpifamilia').empty();

      var jqxhr2 = $.getJSON(urlOrdena, function (result) {
         if (result.ordena === null) {
            $('#saveButton').show();
            var miorden = 1;
         } else if (result.ordena === -1) {
            $('#saveButton').hide();
            $('#badago').toggle();
         } else {
            $('#saveButton').show();
            var miorden = parseInt(result.ordena) + 1;
         }

         $('#fitxafamilia_ordena').val(miorden);
      });


      $("#fitxafamilia_familia").val(aukera).trigger("change");
      var url = global.base + Routing.generate('app_api_getfamiliak', { id: aukera });

      var jqxhr = $.getJSON(url, function (result) {
         $.each(result, function (i, field) {
            $("#cmbAzpifamilia").append($("<option></option>").attr("value", field.id).text(field.familiaeu));
         });
      });

      jqxhr.complete(function () {
         console.log("finito");
         $('#nireloader2').removeClass('loading');
      });

   });

   $('#cmbAzpifamilia').change(function () {
      $('#nireloader2').addClass('loading');
      $('#badago').hide();

      var aukera = $(this).val();
      var fitxaid = $('#fitxa_id').val();
      var urlOrdena = global.base + Routing.generate('api_fitxafamilianextorden', {
         _locale: 'eu',
         fitxa_id: fitxaid,
         familia_id: aukera
      });
      var jqxhr2 = $.getJSON(urlOrdena, function (result) {
         if (result.ordena === null) {
            $('#saveButton').show();
            var miorden = 1;
         } else if (result.ordena === -1) {
            $('#saveButton').hide();
            $('#badago').toggle();
         } else {
            $('#saveButton').show();
            var miorden = parseInt(result.ordena) + 1;
         }

         $('#fitxafamilia_ordena').val(miorden);
      });

      $("#fitxafamilia_familia").val(aukera).trigger("change");

      jqxhr2.complete(function () {
         $('#nireloader2').removeClass('loading');
      })

   });

   $('.btnSeleccionar').click(function () {
      var resul = "";
      var nireParametroak = $('#cmbParametros option').size();
      resul = $('#cmbServiciosDisponibles').val();
      if (nireParametroak > 0) {
         var param = $('#cmbParametros').val();
         resul = resul + param;
      }

      $('#fitxa_parametroa').val(resul);
      $('#myModal').modal('toggle');
   });

   $('.btn-ordena').click(function () {

      $('#nireloader').addClass('loading');
      var miid = $(this).data('id');

      var url = global.base + Routing.generate('fitxafamilia_edit', { _locale: 'eu', id: miid });

      $('#modalOrdenabody').load(url, function () {
         $("#frmfitxafamilia").attr("action", url);
         $('#modalOrdena').modal();
      });

      $('#modalOrdena').on('shown.bs.modal', function (e) {
         $('#nireloader').removeClass('loading');
      });
   });

   $('.btnOrdenaEzabatu').on('click', function () {
      var that = $(this);
      bootbox.confirm({
         title: "Ziur zaude familia ezabatu nahi duzula fitxatik?",
         message: "Fitxatik ezabatuko da familiaren informazioa baina ez Familia bera.",
         buttons: {
            cancel: {
               label: '<i class="fa fa-times"></i> Ezeztatu'
            },
            confirm: {
               label: '<i class="fa fa-check"></i> Onartu'
            }
         },
         callback: function (result) {
            if (result === true) {
               var miid = $(that).data('id');
               console.log("miid =>" + miid);
               var url = global.base + Routing.generate('fitxafamilia_delete', { _locale: 'eu', id: miid });
               console.log("familia delete url => " + url);

               $.ajax({
                  url: url,
                  type: 'delete',
                  success: function (result) {
                     console.log('Delete');
                     $(that).closest("tr").remove();
                  },
                  error: function (e) {
                     console.log(e.responseText);
                  }
               });

            }
         }
      });
   });

   //});

   // Zenbat prozedura gehitu diren gorde
   // var prozeduraCount = '{{ edit_form.prozedurak|length }}';
   // var araudiaCount = '{{ edit_form.araudiak|length }}';
   // var kostuaCount = '{{ edit_form.kostuak|length }}';

   //$(document).ready(function () {
   let params = document.getElementById('app');
   let prozeduraCount = params.dataset.prozeduracount;
   let araudiaCount = params.dataset.araudiacount;
   let kostuaCount = params.dataset.kostuacount;

   // Get the ul that holds the collection of tags
   let $collectionHolder = $('#kostua-fields-list');
   // add a delete link to all of the existing tag form li elements
   $collectionHolder.find('li').each(function () {
      if (!$(this).hasClass("burua"))
         addKostuaFormDeleteLink($(this));
   });

   // Araudiak dituen ul-a jaso
   $collectionHolder = $('#araudia-fields-list');
   // li elementu guztiei ezabatzeko ikonoa jarri
   $collectionHolder.find('li').each(function () {
      if (!$(this).hasClass("burua"))
         addAraudiaFormDeleteLink($(this));
   });
   // Prozedurak dituen ul-a jaso
   $collectionHolder = $('#prozedura-fields-list');
   // li elementu guztiei ezabatzeko ikonoa jarri
   $collectionHolder.find('li').each(function () {
      if (!$(this).hasClass("burua"))
         addProzeduraFormDeleteLink($(this));
   });


   $('#add-prozedura').click(function (e) {
      e.preventDefault();
      //                alert ("sartu da!!");
      var prozeduraList = $('#prozedura-fields-list');

      // grab the prototype template
      var newWidget = prozeduraList.attr('data-prototype');
      // replace the "__name__" used in the id and name of the prototype
      // with a number that's unique to your emails
      // end name attribute looks like name="contact[emails][2]"
      newWidget = newWidget.replace(/__name__/g, prozeduraCount);
      prozeduraCount++;

      // create a new list element and add it to the list
      var newLi = $('<li></li>').html(newWidget);
      newLi.appendTo(prozeduraList);
   });
   $('#add-araudia').click(function (e) {
      e.preventDefault();
      //                alert ("sartu da!!");
      var araudiaList = $('#araudia-fields-list');

      // grab the prototype template
      var newWidget = araudiaList.attr('data-prototype');
      // replace the "__name__" used in the id and name of the prototype
      // with a number that's unique to your emails
      // end name attribute looks like name="contact[emails][2]"
      newWidget = newWidget.replace(/__name__/g, araudiaCount);

      var selectIzena = "#fitxa_araudiak_" + araudiaCount + "_araudia";

      araudiaCount++;

      // create a new list element and add it to the list
      var newLi = $('<li></li>').html(newWidget);
      newLi.appendTo(araudiaList);

      // Ordenatzen
      var customSorter = function (data) {

         return data.sort(function (a, b) {
            a = a.text.toLowerCase();
            b = b.text.toLowerCase();
            if (a > b) {
               return 1;
            } else if (a < b) {
               return -1;
            }
            return 0;
         });

      };


      $(selectIzena).select2({
         ...customSorterSettings, ...{
            width: 'resolve',
         }
      });

   });
   $('#add-kostua').click(function (e) {
      e.preventDefault();
      var locale = "{{ app.request.getLocale() }}";
      $('#cmbOrdenantza').val(-1).trigger("change");
      $('#cmbAtala').empty();
      $("#cmbKontzeptua").empty();
      if (locale === "es") {
         $("#cmbKontzeptua").append($("<option></option>").attr("value", -1).text("Selecciona"));
      } else {
         $("#cmbKontzeptua").append($("<option></option>").attr("value", -1).text("Aukeratu"));
      }


      var kostuaList = $('#kostua-fields-list');
      var newWidget = kostuaList.attr('data-prototype');
      newWidget = newWidget.replace(/__name__/g, kostuaCount);
      $('#dest').val("fitxa_kostuak_" + kostuaCount + "_kostua");
      var ni = ".nirerow" + kostuaCount;
      var niez = parseInt(kostuaCount);
      kostuaCount++;
      var newLi = $('<li></li>').html(newWidget);

      var $removeFormA = '<div class="col-sm-1"><a href="javascript:void(0);" onclick="funcEzabatu(\'.nirerow' + niez + '\')" class="cmdEzabatuRow"><i class="fa fa-times text-danger cmdEzabatuRow"></i></a></div>';
      $(newLi).appendTo($removeFormA);

      newLi.appendTo(kostuaList);

      $(ni).append($removeFormA);
   });
});