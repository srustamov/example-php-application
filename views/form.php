<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
        }

        textarea {
            width: 300px;
            height: 200px;
            resize: none;
            padding: 10px;
        }

        button {
            width: 100px;
            height: 30px;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0b5ed7;
        }

        button:active {
            background-color: #0a58c2;
        }

        .success {
            color: #629755;
        }

        p.success {
            margin-bottom: 10px;
            display: block;
        }

        .error {
            color: #d63333;
        }

    </style>
</head>
<body>

<div class="container">
    <!-- Contact form-->
    <?php if (isset($success)): ?>
        <p class="success">Thank you for your message!</p>
    <?php endif; ?>
    <?php if (isset($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
    <form action="/" method="post">
        <?php echo csrf_field(); ?>
        <label>
            <textarea required name="body" cols="30" rows="10" placeholder="Here goes your message..."><?php echo $body ?? ''; ?></textarea>
        </label>
        <button type="submit">Send</button>
    </form>
</div>


</body>
</html>