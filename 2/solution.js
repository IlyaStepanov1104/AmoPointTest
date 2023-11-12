document.querySelector('select[name="type_val"]').addEventListener('change', function () {
    let selectedType = this.value;
    document.querySelectorAll('input').forEach(input => {
        if (input.getAttribute('name').includes(selectedType)) {
            input.parentElement.style.display = 'block';
        } else {
            input.parentElement.style.display = 'none';
        }
    });
});