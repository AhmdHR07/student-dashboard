<?php require_once 'api.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dev Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="app.js?v=4" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>🎓 Student Dev Dashboard</h1>
            <p>Track your learning hours, tasks, and software projects.</p>
        </header>

        <section class="stats-grid">
            <div class="card">
                <h3>Total Tasks</h3>
                <span id="stat-total-tasks">
                    <?php echo get_total_tasks_count($con); ?>
                </span>
            </div>
            <div class="card">
                <h3>Tasks Completed</h3>
                <span id="stat-completed-tasks" style="color: #10b981;">
                    <?php echo get_completed_tasks_count($con); ?>
                </span>
            </div>
            <div class="card">
                <h3>Tasks Remaining</h3>
                <span id="stat-remaining-tasks" style="color: #f59e0b;">
                    <?php echo get_remaining_tasks_count($con); ?>
                </span>
            </div>
        </section>

        <section class="form-section">
            <h2>➕ Add New Study Log / Task</h2>
            <form id="task-form" action="api.php" method="POST" onsubmit="return validateForm()">
                
                <input 
                    type="text" 
                    id="task-title" 
                    name="task-title" 
                    placeholder="Task description (e.g., Practice SQL Joins)..."
                >

                <select id="task-category" name="task-category">
                    <option value="" disabled selected>Select a category</option>
                    
                    <optgroup label="💻 Computer Science & IT">
                        <option value="Python">Python</option>
                        <option value="JavaScript">JavaScript</option>
                        <option value="SQL & Databases">SQL & Databases</option>
                        <option value="HTML/CSS">HTML/CSS</option>
                        <option value="PHP & Web Dev">PHP & Web Dev</option>
                        <option value="Data Structures & Algorithms">Data Structures & Algorithms</option>
                        <option value="AI & Machine Learning">AI & Machine Learning</option>
                    </optgroup>

                    <optgroup label="📐 Mathematics">
                        <option value="Algebra">Algebra</option>
                        <option value="Calculus">Calculus</option>
                        <option value="Linear Algebra">Linear Algebra</option>
                        <option value="Probability & Statistics">Probability & Statistics</option>
                        <option value="Discrete Mathematics">Discrete Mathematics</option>
                    </optgroup>

                    <optgroup label="🌐 Languages">
                        <option value="English">English</option>
                        <option value="French">French</option>
                        <option value="Arabic">Arabic</option>
                    </optgroup>

                    <optgroup label="⚡ General & Other">
                        <option value="General Study">General Study</option>
                        <option value="Project Planning">Project Planning</option>
                    </optgroup>
                </select>

                <input 
                    type="number" 
                    id="task-hours" 
                    name="task-hours" 
                    placeholder="Target hours..." 
                    step="0.1" 
                    min="0.1"
                >

                <button type="submit" id="submit-btn" name="submit">Save Log</button>
            </form>
        </section>

        <section class="table-section">
            <h2>📋 Your Logs & Tasks</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Task / Topic</th>
                        <th>Category</th>
                        <th>Hours Spent</th>
                        <th>Time Left</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="task-list">
                    <?php 
                    $tasks = get_all_tasks($con);
                    if (mysqli_num_rows($tasks) > 0){
                        $total_count = mysqli_num_rows($tasks);
                        $row_number = $total_count; // Starts counter at total count for DESC order

                        while($row = mysqli_fetch_assoc($tasks)){ 
                            $remaining = (!empty($row['remaining_seconds']) && $row['remaining_seconds'] > 0) 
                                ? intval($row['remaining_seconds']) 
                                : 0;
                            
                            $rem_hrs = floor($remaining / 3600);
                            $rem_mins = floor(($remaining % 3600) / 60);
                            $rem_secs = $remaining % 60;
                            $formatted_remaining = sprintf('%02dh %02dm %02ds', $rem_hrs, $rem_mins, $rem_secs);
                            $is_completed = ($remaining <= 0);
                    ?>
                        <tr>
                            <!-- Display display counter instead of database primary key -->
                            <td>#<?php echo $row_number--; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><span class="badge"><?php echo htmlspecialchars($row['category']); ?></span></td>
                            <td><?php echo number_format($row['hours'], 2); ?> hrs</td>
                            <td>
                                <strong>
                                    <?php echo $is_completed ? "00h 00m 00s" : $formatted_remaining; ?>
                                </strong>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td style="white-space: nowrap;">
                                <?php if ($is_completed): ?>
                                    <span class="badge-complete" style="background-color: #064e3b; color: #34d399; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 0.85rem; display: inline-block;">
                                        ✅ Completed
                                    </span>
                                <?php else: ?>
                                    <button 
                                        class="btn-start" 
                                        onclick="startFocusTimer(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['category'], ENT_QUOTES); ?>', <?php echo $remaining; ?>)"
                                    >
                                        ▶️ Start Focus
                                    </button>
                                <?php endif; ?>

                                <!-- Keeps raw database ID ($row['id']) for AJAX operations -->
                                <button 
                                    class="btn-delete" 
                                    onclick="deleteTask(<?php echo $row['id']; ?>)"
                                    style="background-color: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;"
                                >
                                    🗑️ Delete
                                </button>
                            </td>
                        </tr>
                    <?php } }
                    else{
                    ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #94a3b8;">No study logs found. Add one above!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- 🎯 Full-Screen Focus Window Popup -->
    <div id="focus-overlay" class="focus-modal-hidden">
        <div class="focus-card">
            <span id="focus-task-category" class="focus-tag">CATEGORY</span>
            <h2 id="focus-task-title">Task Title Placeholder</h2>
            
            <div id="timer-display">00:00:00</div>
            
            <div class="focus-controls">
                <button id="btn-pause" onclick="pauseTimer()">⏸ Pause</button>
                <button id="btn-finish" onclick="stopAndSaveSession()">✅ Finish & Return</button>
            </div>
        </div>
    </div>
</body>
</html>