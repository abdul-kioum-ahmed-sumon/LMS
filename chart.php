<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";


$con = new mysqli($servername, $username, $password, $dbname);

$cse_result = mysqli_query($con, "SELECT COUNT(id) as cse FROM students WHERE dept = 'CSE'");
$cse_row = mysqli_fetch_assoc($cse_result);
$cse_count = $cse_row['cse'];

$eee_result = mysqli_query($con, "SELECT COUNT(id) as eee FROM students WHERE dept = 'EEE'");
$eee_row = mysqli_fetch_assoc($eee_result);
$eee_count = $eee_row['eee'];

$bba_result = mysqli_query($con, "SELECT COUNT(id) as bba FROM students WHERE dept = 'BBA'");
$bba_row = mysqli_fetch_assoc($bba_result);
$bba_count = $bba_row['bba'];

$ipe_result = mysqli_query($con, "SELECT COUNT(id) as ipe FROM students WHERE dept = 'IPE'");
$ipe_row = mysqli_fetch_assoc($ipe_result);
$ipe_count = $ipe_row['ipe'];

$CE_result = mysqli_query($con, "SELECT COUNT(id) as CE FROM students WHERE dept = 'CE'");
$CE_row = mysqli_fetch_assoc($CE_result);
$CE_count = $CE_row['CE'];

$me_result = mysqli_query($con, "SELECT COUNT(id) as me FROM students WHERE dept = 'ME'");
$me_row = mysqli_fetch_assoc($me_result);
$me_count = $me_row['me'];

$total_result = mysqli_query($con, "SELECT COUNT(id) as total FROM students");
$total_row = mysqli_fetch_assoc($total_result);
$total_count = $total_row['total'];
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Department', '<?php echo $total_count; ?>'],
            ['CSE', <?php echo $cse_count; ?>],
            ['EEE', <?php echo $eee_count; ?>],
            ['BBA', <?php echo $bba_count; ?>],
            ['IPE', <?php echo $ipe_count; ?>],
            ['CE', <?php echo $CE_count; ?>],
            ['ME', <?php echo $me_count; ?>]
        ]);

        var options = {
            title: 'Distribution of Students by Department',
            pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>
<div id="donutchart" style="width: 900px; height: 500px;"></div>