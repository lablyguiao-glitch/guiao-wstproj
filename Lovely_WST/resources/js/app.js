import './bootstrap';

// Ask before deleting a task.
document.addEventListener('submit', (event) => {
    const form = event.target;

    if (form.dataset.confirm && !window.confirm(form.dataset.confirm)) {
        event.preventDefault();
    }
});

// Save the status as soon as a new option is picked from the dropdown.
document.addEventListener('change', (event) => {
    const form = event.target.closest('[data-status-form]');

    if (!form) return;

    form.closest('.task-row')?.classList.add('is-saving');
    form.submit();
});
