export default class FormLabel {
    /**
     * @param {NodeList} formGroupsNodes
     */
    constructor(formGroupsNodes) {
        this.formGroups = formGroupsNodes;
        this.elements = [];

        this.init();
    }

    addClass() {
        this.parentNode.classList.add('is-filled');
    }

    removeClass() {
        if (!this.value || this.value == "") {
            this.parentNode.classList.remove('is-filled');
        }
    }

    bindEvents() {
        const fields = this.elements;
        for (let i = 0; i < fields.length; i++) {
            const elem = fields[i];
            elem.addEventListener('focusin', this.addClass);
            elem.addEventListener('focusout', this.removeClass);
            elem.addEventListener('input', this.addClass);

            if (elem.value) {
                this.addClass.call(elem);
            }
        }
    }

    getElements() {
        const fieldGroups = this.formGroups;

        for (let i = 0; i < fieldGroups.length; i++) {
            const field = fieldGroups[i].querySelector('input, select, textarea')
            this.elements.push(field)
        }

        this.bindEvents();
    }

    init() {
        this.getElements();
    }
}