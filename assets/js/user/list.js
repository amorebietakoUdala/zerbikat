import '../../css/user/list.css'

import $ from 'jquery';

import 'bootstrap-table';
// import 'bootstrap-table/dist/bootstrap-table.css';
import 'tableexport.jquery.plugin/tableExport.min';
import 'bootstrap-table/dist/extensions/export/bootstrap-table-export'
import 'bootstrap-table/dist/locale/bootstrap-table-es-ES';
import 'bootstrap-table/dist/locale/bootstrap-table-eu-EU';

$(document).ready(function () {
   console.log('user-list ready!!');
   $('#taula').bootstrapTable({
      pagination: true,
      search: true,
      pageSize: 25
   });
});

