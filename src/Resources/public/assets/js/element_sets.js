/**
 * Copyright (C) 2023 Rhyme Digital, LLC.
 *
 * @link		http://rhyme.digital
 * @license		http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

//Namespace
var Rhyme = window.Rhyme || {};

//Encapsulate
(function () {

    // Private

    let _blnDoInit = true,
        _blnInitialized = false;

    /**
     * Setup
     */
    function _setup() {
        if (!_blnDoInit) return;
        _blnDoInit = false;

        _addConfigButtons();

        _blnInitialized = true;
    }

    /**
     * Config buttons
     * @private
     */
    function _addConfigButtons() {
        let form = document.querySelector('[data-vee-element-set-form]');
        if (!form) return;
        let sets = document.querySelectorAll('[data-vee-element-set]');
        if (!sets.length) return;

        sets.forEach((set) => {
            let actions = set.querySelector('.vee-element-set-actions');
            if (!actions) return;
            let radio = set.querySelector('input[name=set]');
            if (!radio || !radio.value || !window.VeeElementSetsFromTable.hasOwnProperty(radio.value)) return;
            let setData = window.VeeElementSetsFromTable[radio.value];

            let configBtn = document.createElement('div');
            configBtn.classList.add('vee-element-set-config');
            actions.prepend(configBtn);

            configBtn.addEventListener('click', (e) => {
                e.preventDefault();
                Backend.openModalIframe({url: setData.edit});
                return false;
            });
        });
    }

    // Public
    Rhyme.ElementSets = {

    };

    document.addEventListener('DOMContentLoaded', _setup);

}());