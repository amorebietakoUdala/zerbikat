import '../../css/common/datatables.css';

// TODO el JSZIP es supuestamente para que funcione el botón de Excel, pero no consigo que funcione, no sé si requiere la versión de Jquery 3 para funcionar o qué.
//import JSZip from 'jszip';
import 'pdfmake';
import './vfs_fonts.js';

import DataTable from 'datatables.net-bs';
import 'datatables.net-buttons-bs';
import 'datatables.net-buttons/js/buttons.html5.mjs';

let table = new DataTable('#taulazerrenda', {
    paging: true,
    searching: true,
    toolbar: true,
    lengthMenu: [
        [10, 25, 50, '-1'],
        [10, 25, 50, 'Lerro guztiak'],
    ],
    layout: {
        topStart: {
            buttons: [
             'pageLength', 'csvHtml5', 'excelHtml5', 'pdfHtml5'
                
            ],
        },
        bottomEnd: {     
            paging: {
                firstLast: false
            }
        }
    },
    language: {
        buttons: {
            pageLength: {
                _: "%d lerro erakutsi",
                '-1': "Lerro guztiak",
            }
        },
        "sProcessing": "Prozesatzen...",
        "sLengthMenu": "Erakutsi _MENU_ erregistro",
        "sZeroRecords": "Ez da emaitzarik aurkitu",
        "sEmptyTable": "Taula hontan ez dago inongo datu erabilgarririk",
        "sInfo": "_START_ -etik _END_ -erako erregistroak erakusten, guztira _TOTAL_ erregistro",
        "sInfoEmpty": "0tik 0rako erregistroak erakusten, guztira 0 erregistro",
        "sInfoFiltered": "(guztira _MAX_ erregistro iragazten)",
        "sInfoPostFix": "",
        "sSearch": "Aurkitu:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Abiarazten...",
        "oPaginate": {
            "sFirst": "Lehena",
            "sLast": "Azkena",
            "sNext": "Hurrengoa",
            "sPrevious": "Aurrekoa"
        },
        "oAria": {
            "sSortAscending": ": Zutabea goranzko eran ordenatzeko aktibatu ",
            "sSortDescending": ": Zutabea beheranzko eran ordenatzeko aktibatu"
        },
    },
});

