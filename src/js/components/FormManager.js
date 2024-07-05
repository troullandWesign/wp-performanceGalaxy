import FormErrorManager from './FormErrorManager'
import FormLabel from './FormLabel'
import { gsap } from 'gsap'

class FormManager extends FormErrorManager {
    constructor() {
        super();
    }

    /**
     * @param {HTMLFormElement} form Form to submit 
     * @param {object} options Options 
     * @param {function|null} options.onSent Callback on XHR success
     * @param {function|null} options.onError Callback on XHR error 
     */
    initForms() {
        new FormLabel(document.querySelectorAll('.js-form-row'));

        // Form validation & submission
        
        const forms = document.querySelectorAll('.js-form');

        if (forms.length > 0) {
            forms.forEach(function (form) {
                var requiredFields = form.querySelectorAll('[data-required]');
                var statusContainer = form.querySelector('.form-status');
                var fields = form.querySelector('.form__fields');
                var successMsg = form.querySelector('.form-sent-message');
                var formBtns = form.querySelector('.form__buttons');
                var responseMsg = form.querySelector('.response-message');

                if (statusContainer) {
                    var statusMessage = statusContainer.querySelector('.form-status__message');
                }

                function onFormSubmitted(response) {
                    //console.log(response)
                    response = JSON.parse(response);

                    form.isPending = false;
                    //console.log(response);

                    if (statusContainer) {
                        statusContainer.classList.remove('is-spinning');
                        statusContainer.classList.add(`is-${response.status}`);
                    }

                    if (responseMsg) {
                        responseMsg.classList.add('visible');
                        responseMsg.classList.add(response.status);
                        responseMsg.textContent = response.message;
                    }

                    if (response.status == 'success') {
                        gsap.to(formBtns, {
                            duration: 0.6,
                            autoAlpha: 0,
                        });

                        if (successMsg) {
                            gsap.to(successMsg, {
                                duration: 0.6,
                                autoAlpha: 1,
                                height: "auto",
                                padding: "3vw",
                            });
                        }

                        if (fields) {
                            if (!fields.classList.contains('no-hide')) {
                                gsap.to(fields, {
                                    duration: 0.6,
                                    autoAlpha: 0,
                                    height: 0,
                                    padding: 0,
                                    margin: 0,
                                });
                            }

                        }

                        if (statusContainer) {
                            gsap.to(statusContainer, {
                                duration: 0,
                                autoAlpha: 0,
                            });
                        }
                    }

                    if (statusMessage) {
                        statusMessage.textContent = response.message;
                    }
                }

                form.addEventListener('submit', function (e) {
                    console.log('submit')
                    e.preventDefault();
                    console.log('submited');
                    if (this.isPending) {
                        return;
                    }

                    let isValid = true;

                    // User input validation
                    requiredFields.forEach(field => {
                        if (field.value.trim() == '') {
                            isValid = false;
                            addErrorMessage(field, 'Ce champ est requis')

                        } else if (!field.checkValidity()) {
                            isValid = false;
                            addErrorMessage(field, field.validationMessage)

                        } else {
                            removeErrorMessage(field);
                        }
                    })

                    // Submit form if all validations passed
                    if (isValid) {
                        this.isPending = true;

                        if (fields) {
                            gsap.to(fields, {
                                duration: 0.6,
                                autoAlpha: 0.5,
                                onComplete: () => {
                                    fields.style.pointerEvents = "none";
                                }
                            });
                        }


                        gsap.to(formBtns, {
                            duration: 0.6,
                            autoAlpha: 0.5,
                        });

                        if (statusContainer) {
                            statusContainer.classList.add('is-visible');
                            statusContainer.classList.add('is-spinning');
                        }

                        submit(form, { onSent: onFormSubmitted });
                    }
                });

                function addErrorMessage(el, error) {
                    if (!el.error) {
                        var span = document.createElement('span');
                        var parent = el.parentNode;

                        span.classList.add('error')
                        span.textContent = error;
                        span.addEventListener('DOMNodeInserted', _bindErrorClass.bind(this, el));

                        parent.appendChild(span);
                        el.error = span;
                    } else {
                        el.error.textContent = error;
                    }
                }

                function _bindErrorClass(el) {
                    el.removeEventListener('DOMNodeInserted', _bindErrorClass);

                    window.getComputedStyle(el).opacity;
                    window.getComputedStyle(el).transform;

                    el.parentNode.classList.add('has-error');
                }



                /* -------------------- */
                /* --- Error remove --- */
                /* -------------------- */

                function removeErrorMessage(el) {
                    if (el.error) {
                        el.error.addEventListener('transitionend', _unbindErrorClass.bind(el));
                        el.parentNode.classList.remove('has-error');
                    }
                }

                function _unbindErrorClass(el) {
                    if (el.error) {
                        el.error.removeEventListener('transitionend', _unbindErrorClass);
                        el.parentNode.removeChild(el.error);
                        el.error = null;
                    }
                }

                function submit(form, options) {
                    let xhr = new XMLHttpRequest();

                    xhr.open('POST', APP.AJAX_URL, true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                    xhr.onloadend = () => options.onSent?.(xhr.response);
                    xhr.onerror = () => options.onError?.()

                    xhr.send(`action=${form.dataset.action}&${form.serialize()}`);
                }
            });
        }
    }
}

export default new FormManager()