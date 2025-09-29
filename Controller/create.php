<?php
require __DIR__ . '/../Database.php';
require __DIR__ . '/../Blog.php';


$db = (new Database())->connect();
$blog = new Blog($db);

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    //handle images
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target = __DIR__ . "/../uploads/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    if ($blog->create($title, $content, $image)) {
        header("Location: ../index.php");
    } else {
        echo "Failed to create post.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
  <label>Title:</label><br>
  <input type="text" name="title" required><br><br>
  <label>Content:</label><br>
  <textarea name="content" rows="5" cols="30" required></textarea><br><br>
  <label>Image:</label><br>
  <input type="file" name="image"><br><br>
  <input type="submit" name="submit" value="Create Post">
</form>
<a href="index.php">Back to blog</a>

