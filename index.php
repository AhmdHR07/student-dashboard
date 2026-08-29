<?php require_once 'api.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dev Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="app.js"></script>
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
                    <?php 
                        $all_tasks = get_all_tasks($con);
                        echo mysqli_num_rows($all_tasks); 
                    ?>
                </span>
            </div>
            <div class="card">
                <h3>Total Hours Spent</h3>
                <span id="stat-total-hours">
                    <?php echo get_total_hours($con); ?> hrs
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
                    <option value="">Select a category</option>
                    <option value="JavaScript">JavaScript</option>
                    <option value="Python">Python</option>
                    <option value="SQL">SQL</option>
                    <option value="HTML/CSS">HTML/CSS</option>
                    <option value="General">General</option>
                </select>

                <input 
                    type="number" 
                    id="task-hours" 
                    name="task-hours" 
                    placeholder="Hours spent..." 
                    step="0.5" 
                    min="0"
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
                        <th>Hours</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody id="task-list">
                    <?php 
                    $tasks = get_all_tasks($con);
                    if (mysqli_num_rows($tasks) > 0){
                        while($row = mysqli_fetch_assoc($tasks)){ 
                    ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><span class="badge"><?php echo htmlspecialchars($row['category']); ?></span></td>
                            <td><?php echo $row['hours']; ?> hrs</td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php } }
                    else{
                    ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8;">No study logs found. Add one above!</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>