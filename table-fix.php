<?php
// Start session
session_start();
include_once(__DIR__ . "/config/config.php");

// Set header
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Style Fix - LMS</title>
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL ?>assets/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/custom.css" />
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #343a40;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .btn-group {
            margin-bottom: 20px;
        }

        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            overflow-x: auto;
        }

        .demo-table-container {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            background-color: white;
        }

        .alert-success pre {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Table Style Fix</h1>
        <p>This tool fixes the styling of tables in the LMS system. It applies a consistent dark header style to all tables.</p>

        <?php
        // Check if CSS has been applied
        $cssApplied = isset($_GET['applied']) && $_GET['applied'] == 'true';

        if ($cssApplied) {
            echo '<div class="alert alert-success">
                <strong>Success!</strong> The CSS styles have been applied. All tables in the system should now have consistent styling.
            </div>';
        }
        ?>

        <div class="btn-group">
            <a href="<?php echo BASE_URL ?>" class="btn btn-secondary">Back to Home</a>
            <a href="<?php echo BASE_URL ?>table-fix.php?applied=true" class="btn btn-success">Apply Table Fixes</a>
        </div>

        <h2>Demo Tables</h2>
        <p>Below are examples of how tables will look after applying the fix.</p>

        <!-- Demo for regular table -->
        <div class="demo-table-container">
            <h3>Regular Table</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>ID Number</th>
                        <th>Dept/Role</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Password</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>S12345</td>
                        <td>Computer Science</td>
                        <td>john@example.com</td>
                        <td>123-456-7890</td>
                        <td><span class="badge text-bg-success">Active</span></td>
                        <td><span class="badge text-bg-success">Yes</span></td>
                        <td>********</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>S67890</td>
                        <td>Electrical Engineering</td>
                        <td>jane@example.com</td>
                        <td>987-654-3210</td>
                        <td><span class="badge text-bg-warning">Pending</span></td>
                        <td><span class="badge text-bg-warning">No</span></td>
                        <td>********</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Demo for striped table -->
        <div class="demo-table-container">
            <h3>Striped Table</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Book Name</th>
                        <th>Publication Year</th>
                        <th>Author Name</th>
                        <th>ISBN No</th>
                        <th>Category</th>
                        <th>Shelf Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Understanding Deep Learning</td>
                        <td>2023</td>
                        <td>Simon J. D. Prince</td>
                        <td>9780262048644</td>
                        <td>Computer Science</td>
                        <td>CSE-12</td>
                        <td><span class="badge text-bg-success">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Harry Potter and the Sorcerer's Stone</td>
                        <td>1997</td>
                        <td>J.K. Rowling</td>
                        <td>978-0590353427</td>
                        <td>Fiction</td>
                        <td>LIT-01</td>
                        <td><span class="badge text-bg-success">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>CSS Code Applied</h2>
        <p>The following CSS code has been added to fix the table styling:</p>

        <pre>
/* Table styling - Improved color scheme */
.table thead th,
.table-dark th,
.table-striped > thead > tr,
.table > thead {
    background-color: #343a40 !important;
    color: white !important;
    font-weight: bold;
    border-color: #454d55 !important;
}

.table th {
    font-weight: bold;
}

/* Fix for striped tables */
.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.025);
}

.table-striped > tbody > tr:nth-of-type(even) {
    background-color: #ffffff;
}

/* Table hover effect */
.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.075);
}

/* Status badges */
.badge.text-bg-success {
    background-color: #28a745 !important;
}

.badge.text-bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.badge.text-bg-danger {
    background-color: #dc3545 !important;
}
        </pre>

        <div class="mt-4">
            <a href="<?php echo BASE_URL ?>" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <script src="<?php echo BASE_URL ?>assets/js/jquery-3.5.1.js"></script>
    <script src="<?php echo BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>