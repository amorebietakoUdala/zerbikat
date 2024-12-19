import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
   static values = {
      id: String,
      mezua: String,
      type: String,
   }

   onClick(e) {
      e.preventDefault();
      var r = confirm(this.mezuaValue);
      if (r == true) {
         if (!this.hasTypeValue || this.typeValue == 'POST') {
            document.getElementById(this.idValue).submit()
         } else if (this.typeValue == 'GET')  {
            document.location.href=e.currentTarget.href;
         }
      }
   }
}