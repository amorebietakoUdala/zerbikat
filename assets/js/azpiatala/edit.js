import $ from 'jquery';

$(document).ready(function() {

   let params = document.getElementById('app');
   let kontzeptuaCount = params.dataset.kontzeptuacount;
   let parrafoaCount = params.dataset.parrafoacount;

   // Parrafoak dituen table-a jaso
   let $collectionHolder = $('#parrafoa-fields-list');
   // tr elementu guztiei ezabatzeko ikonoa jarri
   $collectionHolder.find('tr').each(function()
   {
       if (!$(this).hasClass( "burua" ))
           addParrafoaFormDeleteLink($(this));
   });
   // Kontzeptuak dituen table-a jaso
   $collectionHolder = $('#kontzeptua-fields-list');
   // li elementu guztiei ezabatzeko ikonoa jarri
   $collectionHolder.find('tr').each(function()
   {
       if (!$(this).hasClass( "burua" ))
           addKontzeptuaFormDeleteLink($(this));
   });



   $('#add-parrafoa').click(function(e) {
       e.preventDefault();
//                alert ("sartu da!!");
       var parrafoaList = $('#parrafoa-fields-list');

       // grab the prototype template
       var newWidget = parrafoaList.attr('data-prototype');
       // replace the "__name__" used in the id and name of the prototype
       // with a number that's unique to your emails
       // end name attribute looks like name="contact[emails][2]"
       newWidget = newWidget.replace(/__name__/g, parrafoaCount);
       parrafoaCount++;

       // create a new list element and add it to the list
       var newLi = $('<tr></tr>').html(newWidget);
       newLi.appendTo(parrafoaList);
   });



   $('#add-kontzeptua').click(function(e) {
       e.preventDefault();
//                alert ("sartu da!!");
       var kontzeptuaList = $('#kontzeptua-fields-list');

       // grab the prototype template
       var newWidget = kontzeptuaList.attr('data-prototype');
       // replace the "__name__" used in the id and name of the prototype
       // with a number that's unique to your emails
       // end name attribute looks like name="contact[emails][2]"
       newWidget = newWidget.replace(/__name__/g, kontzeptuaCount);
       kontzeptuaCount++;

       // create a new list element and add it to the list
       var newLi = $('<tr></tr>').html(newWidget);
       newLi.appendTo(kontzeptuaList);
   });

   function addParrafoaFormDeleteLink($tagFormLi) {
       var $removeFormA = $('<td class="col-sm-1"><a href="#"><i class="fa fa-times text-danger"></i></a></td>');
       $tagFormLi.append($removeFormA);

       $removeFormA.on('click', function(e) {
           // prevent the link from creating a "#" on the URL
           e.preventDefault();

           // remove the li for the tag form
           $tagFormLi.remove();
       });
   }
   function addKontzeptuaFormDeleteLink($tagFormLi) {
       var $removeFormA = $('<td class="col-sm-1"><a href="#"><i class="fa fa-times text-danger"></i></a></td>');
       $tagFormLi.append($removeFormA);

       $removeFormA.on('click', function(e) {
           // prevent the link from creating a "#" on the URL
           e.preventDefault();

           // remove the li for the tag form
           $tagFormLi.remove();
       });
   }


})