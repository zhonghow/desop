<?php
$database = new PDO(
    'mysql:host=devkinsta_db;
    dbname=todolist',
    'root',
    'qQs06NBbdQOEMav6'
);

$query = $database->prepare('SELECT * FROM todolists');
$query->execute();

$tasks = $query->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'delete') {
        $statement = $database->prepare("DELETE FROM todolists where id = :id");
        $statement->execute([
            'id' => $_POST['id']
        ]);
        header('Location:/');
        exit;
    }

    if ($_POST['action'] == 'add') {
        $statement = $database->prepare("INSERT INTO todolists(`name`) VALUES (:name)");
        $statement->execute([
            'name' => $_POST['addtask']
        ]);
        header('Location:/');
        exit;
    }

    if ($_POST['action'] == 'checked') {
        if ($_POST['is_checked'] == 0) {
            $statement = $database->prepare("UPDATE todolists set `is_checked` = 1 where id = :id");
        } elseif ($_POST['is_checked'] == 1) {
            $statement = $database->prepare("UPDATE todolists set `is_checked` = 0 where id = :id");
        }
        $statement->execute([
            'id' => $_POST['id']
        ]);
        header('Location:/');
        exit;
    }
}


?>


<!DOCTYPE html>
<html>

<head>
    <title>To Do List | PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
        body {
            background: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="card rounded shadow-sm" style="max-width: 500px; margin: 60px auto;">
        <div class="card-body">
            <h3 class="card-title mb-3">My Todo List</h3>
            <ul class="list-group">

                <?php foreach ($tasks as $task) : ?>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" class="d-flex justify-content-center align-items-center checked">
                                <input type="hidden" name="action" value="checked">
                                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                <input type="hidden" name="is_checked" value="<?php echo $task['is_checked']; ?>">

                                <?php if ($task['is_checked'] == 0) : ?>
                                    <button class="btn btn-sm btn-light">
                                        <i class="bi bi-square"></i>
                                    </button>
                                    <span class="ms-2"><?php echo $task['name']; ?> </span>

                                <?php elseif ($task['is_checked'] == 1) : ?>
                                    <button class="btn btn-sm btn-success">
                                        <i class="bi bi-check-square"></i>
                                    </button>
                                <span class="ms-2 text-decoration-line-through"><?php echo $task['name']; ?> </span>

                                <?php endif; ?>

                            </form>

                        </div>


                        <div>
                            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" class="d-flex justify-content-center align-items-center">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>

                    </li>

                <?php endforeach ?>
            </ul>
            <div class="mt-4">
                <form action="<?php echo $_SERVER['REQUEST_URI'];  ?>" method="POST" class="d-flex justify-content-between align-items-center">
                    <input type="hidden" name="action" value="add">

                    <input type="text" class="form-control" name="addtask" placeholder="Add new item..." required />
                    <button class="btn btn-primary btn-sm rounded ms-2">Add</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>