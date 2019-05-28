<?php
require_once ('tree.php');
if(!isset($_POST['node']) || !isset($_POST['options']))
{
    header("Location: index.php");
    exit();
}
$id=$_POST['node'];
$options=$_POST['options'];
$tree = new Tree('category');
if($id==1)
{
    echo "You can't delete root by this way! If you want to delete root, you have to create new tree!<br>";
    echo '<a href="delete_node.php">Back</a>';
    exit();
}
else if($options==1) $tree->delete_node_without_children($id);
else if($options==2) $tree->delete_node_with_children($id);
header("Location: index.php");
