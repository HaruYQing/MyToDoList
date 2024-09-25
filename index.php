<?php
require "db_conn.php";
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/style.css">
    <title>Vera's To-Do List</title>
</head>

<body>
    <!-- http://localhost/MyToDoList/ -->
    <div class="main-section">
        <div class="add-section">
            <form action="./app/app.php" method="POST" autocomplete="off">
                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>
                    <input
                        type="text"
                        name="title"
                        placeholder="項目名稱必填"
                        style="border-color: #ff6666;">
                    <button type="submit">新增&nbsp;
                        <i class="fa-solid fa-circle-plus"></i>
                    </button>
                <?php } else { ?>
                    <input type="text" name="title" placeholder="請輸入待辦事項">
                    <button type="submit">新增&nbsp;
                        <i class="fa-solid fa-circle-plus"></i>
                    </button>
                <?php } ?>
            </form>
        </div>

        <?php
        $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
        ?>
        <div class="show-todo-section">
            <?php if ($todos->rowCount() <= 0) { ?>
                <div class="todo-item">
                    <div class="empty">
                        <img src="./img/f.png" width="100%">
                        <img src="./img/Ellipsis.gif" width="80px">
                    </div>
                </div>
            <?php } ?>

            <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <span id="<?php echo $todo['id'] ?>"
                        class="remove-to-do">
                        <i class="fa-regular fa-circle-xmark"></i>
                    </span>

                    <?php if ($todo['checked']) { ?>
                        <input
                            type="checkbox"
                            class="check-box"
                            data-todo-id="<?php echo $todo['id'] ?>"
                            checked>
                        <h2 class="checked"><?php echo $todo['title']; ?></h2>
                    <?php } else { ?>
                        <input
                            type="checkbox"
                            class="check-box"
                            data-todo-id="<?php echo $todo['id'] ?>">
                        <h2><?php echo $todo['title']; ?></h2>
                    <?php } ?>
                    <br>
                    <small>建立於：<?php echo $todo['date_time']; ?></small>

                </div>
            <?php } ?>
        </div>
    </div>

    <script src="./js/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.remove-to-do').click(function() {
                const id = $(this).attr('id');
                // alert(id);
                $.post("app/remove.php", {
                        id: id
                    },
                    (data) => {
                        // alert(data);
                        if (data) {
                            $(this).parent().hide(600);
                        }
                    }
                );
            });

            $(".check-box").click(function(e) {
                const id = $(this).attr('data-todo-id');
                // alert(id);
                $.post("app/check.php", {
                        id: id
                    },
                    (data) => {
                        // alert(data);
                        if (data != 'error') {
                            const h2 = $(this).next();
                            if (data === '1') {
                                h2.removeClass('checked');
                            } else {
                                h2.addClass('checked');
                            }
                        }
                    }
                );
            });
        });
    </script>
</body>

</html>