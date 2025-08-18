<?php

// Function to get system counts
function getCounts($conn)
{
    $counts = array(
        'total_books' => 0,
        'total_students' => 0,
        'total_loans' => 0,
        'total_revenue' => 0,
    );

    ## Get books count
    $sql = "select count(id) as total_books from books";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $books = mysqli_fetch_assoc($res);
        $counts['total_books'] = $books['total_books'];
    }

    ## Get students count
    $sql = "select count(id) as total_students from students";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $books = mysqli_fetch_assoc($res);
        $counts['total_students'] = $books['total_students'];
    }

    ## Get active loans count (not returned yet)
    $sql = "select count(id) as total_loans from book_loans WHERE is_return = 0";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $books = mysqli_fetch_assoc($res);
        $counts['total_loans'] = $books['total_loans'];
    }

    ## Get revenue: subscriptions + collected fines
    $sub_total = 0;
    $sql = "select COALESCE(sum(amount),0) as total_revenue from subscriptions";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = mysqli_fetch_assoc($res);
        $sub_total = (int)$row['total_revenue'];
    }

    $fine_total = 0;
    if ($col = $conn->query("SHOW COLUMNS FROM book_loans LIKE 'fine_paid'")) {
        if ($col->num_rows > 0) {
            $res2 = $conn->query("SELECT COALESCE(SUM(fine_paid),0) AS fine_total FROM book_loans");
            if ($res2 && $res2->num_rows > 0) {
                $row2 = mysqli_fetch_assoc($res2);
                $fine_total = (int)$row2['fine_total'];
            }
        }
    }

    $counts['total_revenue'] = $sub_total + $fine_total;

    return $counts;
}

// Admin notifications helper
function getAdminNotifications(mysqli $conn)
{
    // New student registrations waiting for approval
    $studentsSql = "SELECT id, name, email, created_at FROM students WHERE verified = 0 ORDER BY created_at DESC LIMIT 10";
    $students = $conn->query($studentsSql);

    // New book reservations not issued yet
    $loansSql = "SELECT l.id as booking_id, b.title, s.name as student_name, l.created_at
                 FROM book_loans l
                 JOIN books b ON b.id = l.book_id
                 JOIN students s ON s.id = l.student_id
                 WHERE l.is_return = 0 AND l.issued_at IS NULL
                 ORDER BY l.created_at DESC
                 LIMIT 10";
    $loans = $conn->query($loansSql);

    return [
        'pending_students' => $students,
        'pending_loans' => $loans
    ];
}

// Function to get tab data
function getTabData($conn)
{
    $tabs = array(
        'students' => array(),
        'loans' => array(),
        'subscriptions' => array(),
    );

    ## Get recent students
    $sql = "select * from students order by id desc limit 5";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $tabs['students'][] = $row;
        }
    }

    ## Get recent loans
    $sql = "select l.*, b.title as book_title, s.name as student_name 
        from book_loans l
        inner join books b on b.id = l.book_id
        inner join students s on s.id = l.student_id
        order by l.id desc limit 5";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $tabs['loans'][] = $row;
        }
    }

    ## Get recent subscriptions
    $sql = "select s.*, p.title as plan_name, st.name as student_name 
        from subscriptions s
        inner join subscription_plans p on p.id = s.plan_id
        inner join students st on st.id = s.student_id 
        order by s.id desc limit 5";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $tabs['subscriptions'][] = $row;
        }
    }

    return $tabs;
}
