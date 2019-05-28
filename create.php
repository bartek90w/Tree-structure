<?php
require_once ('tree.php');
if(!isset($_POST['name']))
{
    $tree = new Tree('category');
    $tree->create_tree();
    header("Location: index.php");
    exit();
}
$name=$_POST['name'];
if(preg_match('/^[a-zA-Z0-9 .]+$/', $name) && !preg_match('/^\s*$/', $name))
{
    $tree = new Tree('category');
    $tree->create_tree($name);
    header("Location: index.php");
}
else echo "Root name can have only letters, numbers and spaces! Root name can't have white spaces only!<br>";
echo '<a href="create_tree.php">Back</a>';
