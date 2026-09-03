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

let timerInterval = null;
let activeTaskId = null;
let currentRemainingSeconds = 0;
let secondsStudiedThisSession = 0;
let isPaused = false;

function startFocusTimer(taskId, taskTitle, taskCategory, remainingSecs) {
    if (remainingSecs <= 0) {
        alert("This task is already completed!");
        return;
    }

    activeTaskId = taskId;
    currentRemainingSeconds = (remainingSecs && remainingSecs >= 0) ? remainingSecs : 0;
    secondsStudiedThisSession = 0;
    isPaused = false;

    const titleElem = document.getElementById('focus-task-title');
    if (titleElem) titleElem.innerText = taskTitle;

    const categoryElem = document.getElementById('focus-task-category');
    if (categoryElem) categoryElem.innerText = taskCategory.toUpperCase();

    const pauseBtn = document.getElementById('btn-pause');
    if (pauseBtn) pauseBtn.innerText = "⏸ Pause";

    const overlay = document.getElementById('focus-overlay');
    if (overlay) {
        overlay.classList.remove('focus-modal-hidden');
        overlay.classList.add('focus-modal-visible');
    }

    updateTimerDisplay();

    if (timerInterval) clearInterval(timerInterval);

    timerInterval = setInterval(() => {
        if (!isPaused) {
            if (currentRemainingSeconds > 0) {
                currentRemainingSeconds--;
                secondsStudiedThisSession++;
                updateTimerDisplay();
            } else {
                clearInterval(timerInterval);
                alert("🎉 Focus session completed!");
                stopAndSaveSession();
            }
        }
    }, 1000);
}

function updateTimerDisplay() {
    const hrs = Math.floor(currentRemainingSeconds / 3600);
    const mins = Math.floor((currentRemainingSeconds % 3600) / 60);
    const secs = currentRemainingSeconds % 60;

    const formattedTime = 
        `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

    const displayElem = document.getElementById('timer-display');
    if (displayElem) displayElem.innerText = formattedTime;
}

function pauseTimer() {
    isPaused = !isPaused;
    const pauseBtn = document.getElementById('btn-pause');
    if (pauseBtn) {
        pauseBtn.innerText = isPaused ? "▶️ Resume" : "⏸ Pause";
    }
}

function stopAndSaveSession() {
    if (timerInterval) clearInterval(timerInterval);

    const overlay = document.getElementById('focus-overlay');

    if (secondsStudiedThisSession === 0) {
        if (overlay) {
            overlay.classList.remove('focus-modal-visible');
            overlay.classList.add('focus-modal-hidden');
        }
        return;
    }

    fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            'action': 'update_time',
            'task_id': activeTaskId,
            'studied_seconds': secondsStudiedThisSession,
            'remaining_seconds': currentRemainingSeconds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (overlay) {
            overlay.classList.remove('focus-modal-visible');
            overlay.classList.add('focus-modal-hidden');
        }
        window.location.reload();
    })
    .catch(err => {
        console.error("Error updating session:", err);
        window.location.reload();
    });
}

function deleteTask(taskId) {
    if (confirm("Are you sure you want to delete this task?")) {
        fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                'action': 'delete_task',
                'task_id': taskId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.reload();
            } else {
                alert("Error deleting task: " + (data.message || "Unknown error"));
            }
        })
        .catch(err => {
            console.error("Error deleting task:", err);
            window.location.reload();
        });
    }
}