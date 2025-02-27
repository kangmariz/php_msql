<?php
include 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold text-center mb-6">Add a New Guest</h1>

        <form method="post" action="edit.php" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
            <label class="block mb-2 font-semibold">First Name:</label>
            <input type="text" name="first_name" required class="w-full p-2 border rounded-md mb-4">

            <label class="block mb-2 font-semibold">Last Name:</label>
            <input type="text" name="last_name" required class="w-full p-2 border rounded-md mb-4">

            <label class="block mb-2 font-semibold">Email:</label>
            <input type="email" name="email" required class="w-full p-2 border rounded-md mb-4">

            <button type="submit" name="add" class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 transition">
                Add Guest
            </button>
        </form>

        <h2 class="text-xl font-semibold text-center mt-8">Guest Table View</h2>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-green-500 text-white">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">First Name</th>
                        <th class="p-3">Last Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Registration Date</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, first_name, last_name, email, reg_date FROM guest_info";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b hover:bg-gray-100 transition'>
                                    <td class='p-3 text-center'>{$row['id']}</td>
                                    <td class='p-3 text-center'>{$row['first_name']}</td>
                                    <td class='p-3 text-center'>{$row['last_name']}</td>
                                    <td class='p-3 text-center'>{$row['email']}</td>
                                    <td class='p-3 text-center'>{$row['reg_date']}</td>
                                    <td class='p-3 text-center'>
                                        <a href='edit.php?edit_id={$row['id']}' class='bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition'>Edit</a>
                                        <a href='delete.php?delete_id={$row['id']}' class='bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center p-4 text-gray-500'>No guests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
