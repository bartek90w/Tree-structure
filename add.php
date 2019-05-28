<?php
require_once ('tree.php');
if(!isset($_POST['parent']) || !isset($_POST['name']))
{
    header("Location: index.php");
    exit();
}
$id=$_POST['parent'];
$name=$_POST['name'];
if(preg_match('/^[a-zA-Z0-9 .]+$/', $name) && !preg_match('/^\s*$/', $name))
{
    $tree = new Tree('category');
    $tree->add_node($id,$name);
    header("Location: index.php");
}
else echo "Category name can have only letters, numbers and spaces! Category name can't have white spaces only!<br>";
echo '<a href="add_node.php">Back</a>';
