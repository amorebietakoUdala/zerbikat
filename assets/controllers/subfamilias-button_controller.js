import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
   static targets = ['button'];

   onClick(e) {
      this.buttonTargets.forEach(this.reset);
      this.toggle(e.currentTarget);
   }

   onListClose(e) {
      const clickedButton = document.getElementById(e.detail.button);
      this.toggle(clickedButton);
   }

   toggle(button) {
      const number = this.extractNumber(button.id);
      const isActive = button.classList.contains('famButtonActive');

      if (isActive) {
         this.deactivate(button, number);
      } else {
         this.activate(button, number);
      }
   }

   reset = (button) => {
      const number = this.extractNumber(button.id);
      this.deactivate(button, number);
   }

   activate(button, number) {
      button.classList.add('famButtonActive', `bg-fam-${number}-active`);
   }

   deactivate(button, number) {
      button.classList.remove('famButtonActive', `bg-fam-${number}-active`);
   }

   extractNumber(id) {
      const firstDash = id.indexOf('-');
      const lastDash = id.lastIndexOf('-');
      return id.slice(firstDash + 1, lastDash);
   }
}