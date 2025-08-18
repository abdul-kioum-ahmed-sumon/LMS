<?php
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/settings.php");
include_once(DIR_URL . "models/loan.php");

// Load values
$dailyFine = getSetting($conn, 'daily_fine_taka', (string)DAILY_FINE_TAKA);
$borrowDays = getSetting($conn, 'borrow_days', '15');

// Save
if (isset($_POST['save_settings'])) {
    $fine = isset($_POST['daily_fine_taka']) ? trim($_POST['daily_fine_taka']) : '';
    $borrow = isset($_POST['borrow_days']) ? trim($_POST['borrow_days']) : '';
    if ($fine !== '' && ctype_digit($fine) && (int)$fine >= 0) {
        setSetting($conn, 'daily_fine_taka', $fine);
        if ($borrow !== '' && ctype_digit($borrow) && (int)$borrow >= 1) {
            setSetting($conn, 'borrow_days', $borrow);
            $_SESSION['success'] = 'Settings saved.';
            header('Location: ' . BASE_URL . 'settings.php');
            exit;
        } else {
            $_SESSION['error'] = 'Borrow time limit must be a positive integer (days).';
        }
    } else {
        $_SESSION['error'] = 'Daily fine must be a non-negative integer.';
    }
}

include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/topbar.php");
include_once(DIR_URL . "include/sidebar.php");
?>

<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-4">
                <?php include_once(DIR_URL . "include/alerts.php"); ?>
                <h3 class="fw-bold text-uppercase">System Settings</h3>
            </div>

            <div class="col-md-8 mt-3">
                <div class="card">
                    <div class="card-header">Fine Management</div>
                    <div class="card-body">
                        <form method="post" action="<?php echo BASE_URL; ?>settings.php">
                            <div class="mb-3">
                                <label class="form-label">Daily Fine (Taka) after 15 days</label>
                                <input type="number" min="0" class="form-control" name="daily_fine_taka" value="<?php echo htmlspecialchars($dailyFine); ?>" required />
                                <small class="text-muted">This amount is charged per day after the due date.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Borrow Time Limit (days)</label>
                                <select class="form-select" name="borrow_days" required>
                                    <?php
                                    $options = [7, 10, 14, 15, 20, 21, 30];
                                    foreach ($options as $opt) {
                                        $sel = ((string)$opt === (string)$borrowDays) ? 'selected' : '';
                                        echo '<option value="' . $opt . '" ' . $sel . '>' . $opt . ' days</option>';
                                    }
                                    ?>
                                </select>
                                <small class="text-muted">Default loan period before fines start.</small>
                            </div>
                            <button type="submit" name="save_settings" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once(DIR_URL . "include/footer.php"); ?>