<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!empty($id) && !empty($first_name) && !empty($last_name) && !empty($email)) {
        $sql = "UPDATE guest_info SET first_name=?, last_name=?, email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $first_name, $last_name, $email, $id);

        if ($stmt->execute()) {
            echo "<script> window.location='index.php';</script>";
        } else {
            echo "<p class='text-red-500 text-center'>Error: " . $stmt->error . "</p>";
        }
    }
}

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM guest_info WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $edit_first_name = $row['first_name'];
        $edit_last_name = $row['last_name'];
        $edit_email = $row['email'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Guest</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold text-center mb-6">Edit Guest</h1>

        <form method="post" action="edit.php" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
            <input type="hidden" name="id" value="<?= htmlspecialchars($edit_id) ?>">

            <label class="block mb-2 font-semibold">First Name:</label>
            <input type="text" name="first_name" required value="<?= htmlspecialchars($edit_first_name) ?>" class="w-full p-2 border rounded-md mb-4">

            <label class="block mb-2 font-semibold">Last Name:</label>
            <input type="text" name="last_name" required value="<?= htmlspecialchars($edit_last_name) ?>" class="w-full p-2 border rounded-md mb-4">

            <label class="block mb-2 font-semibold">Email:</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($edit_email) ?>" class="w-full p-2 border rounded-md mb-4">

            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 transition">
                Update Guest
            </button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
