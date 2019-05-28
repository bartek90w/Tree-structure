<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete node</title>
</head>
<body>
<form action="delete.php" method="post">
    Node: <select name="node">
        <?php
        require_once ('tree.php');
        $tree = new Tree('category');
        $data = $tree->get_tree(1);
        foreach($data as $row)
        {
            echo '<option value="'. $row['id'] . '">';
            for($i=0;$i<$row['level'];$i++) echo "&nbsp&nbsp";
            echo $row['name'] . '</option>';
        }
        ?>
    </select><br>
    <label>
        <input type="radio" name="options" value="1" checked>
        Delete without children
    </label>
    <label>
        <input type="radio" name="options" value="2">
        Delete with children
    </label><br>
    <input type="submit" value="Submit">

</form>
<a href ="admin.html">Back</a>
</body>
</html>