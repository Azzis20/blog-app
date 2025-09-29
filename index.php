<?php
require __DIR__ . '/Database.php';    
require __DIR__ . '/Blog.php';         

$db = (new Database())->connect();
$blog = new Blog($db);

$posts = $blog->getAllPost();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .create-btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 0.8rem 2rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 1rem;
            transition: background-color 0.2s;
        }

        .create-btn:hover {
            background: #2980b9;
        }

        .posts-container {
            display: grid;
            gap: 2rem;
        }

        .post {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .post:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .post h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .post p {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .post img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .post-meta {
            font-size: 0.9rem;
            color: #95a5a6;
            margin-bottom: 1rem;
        }

        .actions {
            display: flex;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #ecf0f1;
        }

        .actions a {
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .edit-btn {
            background: #f39c12;
            color: white;
        }

        .edit-btn:hover {
            background: #d68910;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background: #c0392b;
        }

        .no-posts {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 3rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .success-message {
            background: #2ecc71;
            color: white;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Blog Posts</h2>
            <a href="Controller/create.php" class="create-btn">Create New Post</a>
        </div>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
            <div class="success-message">
                Post deleted successfully!
            </div>
        <?php endif; ?>

        <div class="posts-container">
            <?php if (empty($posts)): ?>
                <div class="no-posts">
                    <p>No blog posts found. Create your first post to get started!</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($post['context'])); ?></p>
                        <?php if ($post['image']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Blog Image">
                        <?php endif; ?>
                        <div class="post-meta">
                            Posted on: <?php echo date('F j, Y \a\t g:i A', strtotime($post['created_at'])); ?>
                        </div>
                        <div class="actions">
                            <a href="Controller/edit.php?id=<?php echo $post['id']; ?>" class="edit-btn">Edit</a>
                            <a href="Controller/delete.php?id=<?php echo $post['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>