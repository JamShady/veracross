import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    Array.from(document.querySelectorAll('input[type="tel"]'))
        .pop() // get the last one
        ?.addEventListener('input', e => {
            const source = e.target;
            const value = source.value.trim()

            if (value) {
                source.value = '';

                const parent = source.closest('div.row');
                const clone = parent.cloneNode(true);
                const target = clone.querySelector('input')

                parent.parentNode.insertBefore(clone, parent);
                target.value = value;
                target.focus();
            }
        })

});
