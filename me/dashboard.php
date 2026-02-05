<?php
require_once '../foodDB.php';
session_start();

// check if the session variable is exist
if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}
// check if the user is not admin or staff
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff') {
    header("Location: warning.php");
    exit();
}

// display the number of users
$countusersql = "SELECT COUNT(*) FROM users WHERE userId";
$stmtcount = $conn->prepare($countusersql);
$stmtcount->execute();
$stmtcount->bind_result($totalUsers);
$stmtcount->fetch();
$stmtcount->close();

// display the number of admins
$countadminsql = "SELECT COUNT(*) FROM users WHERE role = 'admin'";
$stmtcount = $conn->prepare($countadminsql);
$stmtcount->execute();
$stmtcount->bind_result($totalAdmins);
$stmtcount->fetch();
$stmtcount->close();

// display the number of staffs
$countstaffsql = "SELECT COUNT(*) FROM users WHERE role = 'staff'";
$stmtcount = $conn->prepare($countstaffsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStaffs);
$stmtcount->fetch();
$stmtcount->close();

// display the number of customers
$countcustomersql = "SELECT COUNT(*) FROM users WHERE role = 'user'";
$stmtcount = $conn->prepare($countcustomersql);
$stmtcount->execute();
$stmtcount->bind_result($totalCustomers);
$stmtcount->fetch();
$stmtcount->close();
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']) ?></h2>
<p>this is a dashboard page, also as a protected page.</p>
<p>total <?php echo $totalUsers; ?> users</p>
<p>total <?php echo $totalAdmins; ?> admins</p>
<p>total <?php echo $totalStaffs; ?> staffs</p>
<p>total <?php echo $totalCustomers; ?> customers</p>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Contact Number</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <!--- display all users --->
        <?php
        $filter = "";
        $usersQuery = "SELECT userId, fullName, email, address, contactNumber, role FROM users WHERE 1" . $filter;
        $usersQuery .= " ORDER BY users.userId";
        $usersResult = $conn->query($usersQuery);
        while ($user = $usersResult->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $user['userId']; ?></td>
                <td><?php echo $user['fullName']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <?php if ($user['address']):  ?>
                    <span><?php echo $user['address']; ?></span>
                    <?php else: ?>
                        <span>-</span>
                        <?php endif; ?>
                </td>
                <td><?php echo $user['contactNumber']; ?></td>
                <td><?php echo $user['role']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    <tbody>

    </tbody>
</table>
<a href="/web/me/sign-out.php">logout</a>