import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static values = {
    id: String,
    button: String,
    childrenClass: String,
  }

  onClick() {
    const target = document.getElementById(this.idValue);
    const isHidden = target.style.display === 'none' || getComputedStyle(target).display === 'none';

    if (isHidden) {
      this.hideAll();
      target.style.display = 'block';
      target.closest('.familia').scrollIntoView({ behavior: 'smooth', block: 'start' });;
      this.dispatch('open');
    } else {
      target.style.display = 'none';
      this.dispatch('close', {
        detail: { button: this.buttonValue }
      });
    }
  }

  hideAll() {
    document.querySelectorAll(`.${this.childrenClassValue}`).forEach(el => {
      el.style.display = 'none';
    });
  }
}