import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
   static values = {
      id: String,
   }

   onClick(e) {
      if (document.getElementById(this.idValue).style.display == 'none') {
         document.getElementById(this.idValue).style.display = 'block';
      }
      else if (document.getElementById(this.idValue).style.display == 'block') {
         document.getElementById(this.idValue).style.display = 'none';
      }
   }
}