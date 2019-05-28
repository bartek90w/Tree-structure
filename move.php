<?php
require_once ('tree.php');
if(!isset($_POST['node']) || !isset($_POST['parent']))
{
    header("Location: index.php");
    exit();
}
$id=$_POST['node'];
$parent_id=$_POST['parent'];
$tree = new Tree('category');
$subtree=$tree->get_tree($id);
$subtree[]=$tree->get_node($id);
foreach($subtree as $row)
{
    if($row['id'] == $parent_id)
    {
        echo "You can't move node under its children and the parent can't be same as child!<br>";
        echo '<a href="move_node.php">Back</a>';
        exit();
    }
}
$tree->move_node($id,$parent_id);
header("Location: index.php");
