<?php
require __DIR__ . '/../Database.php';
require __DIR__ . '/../Blog.php';

$db = (new Database())->connect();
$blog = new Blog($db);

$error = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "No valid post ID provided.";
    exit;
}

$post = $blog->getById($_GET['id']);

if (!$post) {
    echo "Post not found.";
    exit;
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];  // Changed from $_POST['context'] to $_POST['content']
    $image = null;

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $error = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
        } elseif ($_FILES['image']['size'] > $max_size) {
            $error = "File too large. Maximum size is 5MB.";
        } else {
            $image = time() . "_" . basename($_FILES['image']['name']);
            $target = __DIR__ . "/../uploads/" . $image;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $error = "Failed to upload image.";
            }
        }
    }

    if (!$error && $blog->update($post["id"], $title, $content, $image)) {
        header("Location: ../index.php");
        exit;
    } elseif (!$error) {
        $error = "Failed to update post.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Simple Blog</title>
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
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        h1 {
            font-size: 2rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .subtitle {
            color: #7f8c8d;
            margin-bottom: 3rem;
        }

        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e1e8ed;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
            font-family: inherit;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        input[type="file"] {
            padding: 0.5rem;
            border: 2px dashed #ddd;
            border-radius: 6px;
            width: 100%;
            background: #f9f9f9;
        }

        .current-image {
            margin-bottom: 1rem;
        }

        .current-image img {
            max-width: 200px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .image-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #f39c12;
            color: white;
        }

        .btn-primary:hover {
            background: #d68910;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .error {
            background: #e74c3c;
            color: white;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .file-info {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>
        <p class="subtitle">Make your changes and update your post</p>

        <div class="form-container">
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" required><?php echo htmlspecialchars($post['context']); ?></textarea>
                </div>

                <div class="form-group">
                    <?php if ($post['image']): ?>
                        <div class="current-image">
                            <div class="image-label">Current image:</div>
                            <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Current post image">
                        </div>
                    <?php endif; ?>
                    
                    <label for="image">Update Image (optional)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div class="file-info">Leave empty to keep current image. Max size: 5MB. Accepted formats: JPEG, PNG, GIF</div>
                </div>

                <div class="btn-group">
                    <button type="submit" name="submit" class="btn btn-primary">Update Post</button>
                    <a href="../index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 