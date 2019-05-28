<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit node</title>
</head>
<body>
<form action="edit.php" method="post">
    Node: <select name="node">
        <?php
        require_once ('tree.php');
        $tree = new Tree('category');
        $data = $tree->get_tree(1);
        $row = $tree->get_node(1);
        echo '<option value="'. $row['id'] . '">'. $row['name'] . '</option>';
        foreach($data as $row)
        {
            echo '<option value="'. $row['id'] . '">';
            for($i=0;$i<=$row['level'];$i++) echo "&nbsp&nbsp";
            echo $row['name'] . '</option>';
        }
        ?>
    </select><br>
    New name: <input type="text" name="name"><br>
    <input type="submit" value="Submit">

</form>
<a href ="admin.html">Back</a>
</body>
</html>