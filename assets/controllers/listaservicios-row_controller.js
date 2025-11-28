import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
   static targets = ['rowButton', "row"];

   onMouseOver() {
      this.rowButtonTarget.classList.toggle("btnActive");
      this.rowTarget.classList.toggle("fitxa-lerroa-active");
   }

   onMouseOut() {
      this.rowButtonTarget.classList.toggle("btnActive");
      this.rowTarget.classList.toggle("fitxa-lerroa-active");
   }

}