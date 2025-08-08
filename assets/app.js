/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './css/app.css';

// start the Stimulus application
import './bootstrap';

import 'bootstrap';

import $ from 'jquery';

/* <script type="text/javascript" src="{{ asset('build/js/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('build/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('build/js/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('build/js/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('build/js/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('build/js/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('build/js/buttons.html5.min.js') }}"></script> #} */

import './js/common/datatables.js';

global.$ = $;
global.jQuery = $;
const htmlElement = document.documentElement;
const baseValue = htmlElement.getAttribute('base') || '';
global.base = baseValue;
global.locale = $('html').attr("lang");

function ajustarPadding() {
   var alturaTotal = 0;
   document.querySelectorAll('.barra-fija').forEach(function(nav) {
     nav.style.position = "fixed";
     nav.style.top = alturaTotal+"px";
     alturaTotal += nav.offsetHeight;
   });
   var mainDiv = document.querySelector('div[role="main"]');
   mainDiv.style.paddingTop = ( alturaTotal ) + 'px';
 }

window.addEventListener('load', ajustarPadding);
window.addEventListener('resize', ajustarPadding);
