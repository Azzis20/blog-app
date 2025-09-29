<?php
require __DIR__ . '/../Database.php';
require __DIR__ . '/../Blog.php';


$db = (new Database())->connect();
$blog = new Blog($db);

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get post to delete its image
    $post = $blog->getById($id);
    if($post && $blog->delete($id)) {
        // Delete associated image file
        if(!empty($post['image']) && file_exists(__DIR__ . "/../uploads/" . $post['image'])) {
            unlink(__DIR__ . "/../uploads/" . $post['image']);
        }
        header("Location: ../index.php?deleted=1");
        exit;
    } else {
        $error = "Failed to delete post.";
    }
} else {
    $error = "Invalid post ID.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post - Simple Blog</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fafafa;
        }

        .container {
            max-width: 500px;
            margin: 4rem auto;
            padding: 2rem 1rem;
        }

        .error-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .error-icon {
            font-size: 3rem;
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        h1 {
            color: #e74c3c;
            margin-bottom: 1rem;
            font-weight: 400;
        }

        p {
            color: #7f8c8d;
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <div class="error-icon">⚠️</div>
            <h1>Error</h1>
            <p><?php echo htmlspecialchars($error); ?></p>
            <a href="../index.php" class="btn">Back to Blog</a>
        </div>
    </div>
</body>
</html>