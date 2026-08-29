function validateForm() {
    const titleInput = document.getElementById('task-title');
    const categorySelect = document.getElementById('task-category');
    const hoursInput = document.getElementById('task-hours');

    const title = titleInput.value.trim();
    const category = categorySelect.value;
    const hours = parseFloat(hoursInput.value);

    if (title === '') {
        alert('Please enter a task description.');
        titleInput.focus();
        return false;
    }

    if (title.length < 3) {
        alert('Task description must be at least 3 characters long.');
        titleInput.focus();
        return false;
    }

    if (category === '') {
        alert('Please select a valid category.');
        categorySelect.focus();
        return false;
    }

    if (isNaN(hours) || hours <= 0) {
        alert('Please enter a valid number of hours (greater than 0).');
        hoursInput.focus();
        return false;
    }

    if (hours > 24) {
        alert('Hours spent cannot exceed 24 hours for a single log.');
        hoursInput.focus();
        return false;
    }

    return true;
}