//import '../../css/familia/edit.css';

import $ from 'jquery';

import '../common/select2.js';

$(function () {
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

   const options = {
      theme: 'bootstrap',
      tags: false,
      sorter: customSorter,
   };

   $('#familia_parent').select2(options);
});