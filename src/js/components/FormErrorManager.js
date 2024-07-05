export default class FormErrorManager {

    /* ----------------- */
    /* --- Error add --- */
    /* ----------------- */

    addErrorMessage(el, error) {
        if (!el.error) {
            var span = document.createElement('span');
            var parent = el.parentNode;

            span.classList.add('error')
            span.textContent = error;
            span.addEventListener('DOMNodeInserted', this._bindErrorClass.bind(this, el));

            parent.appendChild(span);
            el.error = span;
        } else {
            el.error.textContent = error;
        }
    }

    _bindErrorClass(el) {
        el.removeEventListener('DOMNodeInserted', this._bindErrorClass);

        window.getComputedStyle(el).opacity;
        window.getComputedStyle(el).transform;

        el.parentNode.classList.add('has-error');
    }



    /* -------------------- */
    /* --- Error remove --- */
    /* -------------------- */

    removeErrorMessage(el) {
        if (el.error) {
            el.error.addEventListener('transitionend', this._unbindErrorClass.bind(this, el));
            el.parentNode.classList.remove('has-error');
        }
    }

    _unbindErrorClass(el) {
        if (el.error) {
            el.error.removeEventListener('transitionend', this._unbindErrorClass);
            el.parentNode.removeChild(el.error);
            el.error = null;
        }
    }
}