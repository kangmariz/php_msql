<?php
include 'database.php';

if (isset($_POST['add'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $photo = NULL;
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $photo_name = time() . "_" . basename($_FILES['photo']['name']);
        $photo = $target_dir . $photo_name;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
            $photo = NULL;
        }
    }

    $document = NULL;
    if (!empty($_FILES['document']['name'])) {
        $doc_dir = "documents/";
        if (!file_exists($doc_dir)) {
            mkdir($doc_dir, 0777, true);
        }
        $doc_name = time() . "_" . basename($_FILES['document']['name']);
        $document = $doc_dir . $doc_name;

        if (!move_uploaded_file($_FILES['document']['tmp_name'], $document)) {
            $document = NULL;
        }
    }

    $stmt = $conn->prepare("INSERT INTO guest_info (first_name, last_name, email, photo, document) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $photo, $document);

    if ($stmt->execute()) {
        echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error adding guest: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-200">

    <header class="bg-gradient-to-r from-green-400 to-blue-500 p-6 text-white text-center text-3xl font-bold shadow-md">
        Guest Management System
    </header>

    <div class="container mx-auto p-8">
        <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl">
            <h1 class="text-2xl font-bold text-center mb-4">Add a New Guest</h1>
            
            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block font-semibold">First Name</label>
                    <input type="text" name="first_name" required class="w-full p-3 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Last Name</label>
                    <input type="text" name="last_name" required class="w-full p-3 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Email</label>
                    <input type="email" name="email" required class="w-full p-3 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Photo</label>
                    <input type="file" name="photo" class="w-full p-3 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Upload Document</label>
                    <input type="file" name="document" class="w-full p-3 border rounded-md">
                </div>

                <button type="submit" name="add" class="w-full bg-green-500 text-white py-3 rounded-md hover:bg-green-600 transition font-semibold">
                    Add Guest
                </button>
            </form>
        </div>

        <h2 class="text-xl font-semibold text-center mt-10">Guest Table</h2>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <thead class="bg-green-500 text-white">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Photo</th>
                        <th class="p-4">Document</th>
                        <th class="p-4">First Name</th>
                        <th class="p-4">Last Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Registration Date</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT id, first_name, last_name, email, reg_date, photo, document FROM guest_info";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition'>
                                <td class='p-4 text-center'>{$row['id']}</td>
                                <td class='p-4 text-center'>";
                        
                        if (!empty($row['photo'])) {
                            echo "<img src='{$row['photo']}' alt='Guest Photo' class='w-12 h-12 rounded-full mx-auto'>";
                        } else {
                            echo "<span class='text-gray-500'>No photo</span>";
                        }

                        echo "</td>
                                <td class='p-4 text-center'>";
                        
                        if (!empty($row['document'])) {
                            echo "<a href='{$row['document']}' class='text-blue-500 underline' target='_blank'>View</a>";
                        } else {
                            echo "<span class='text-gray-500'>No document</span>";
                        }

                        echo "</td>
                                <td class='p-4 text-center'>{$row['first_name']}</td>
                                <td class='p-4 text-center'>{$row['last_name']}</td>
                                <td class='p-4 text-center'>{$row['email']}</td>
                                <td class='p-4 text-center'>{$row['reg_date']}</td>
                                <td class='p-4 text-center flex justify-center space-x-2'>
                                    <a href='edit.php?edit_id={$row['id']}' class='bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition'>
                                        Edit
                                    </a>
                                    <a href='delete.php?delete_id={$row['id']}' class='bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition' onclick='return confirm(\"Are you sure?\")'>
                                        Delete
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center p-4 text-gray-500'>No guests found.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
