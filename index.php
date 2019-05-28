<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tree</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
require_once ('tree.php');
$tree = new Tree('category');
$i=0;
$data = $tree->get_tree(1);
$row = $tree->get_node(1);
if(!empty($data)){
    echo "<ul id='menu'>";
    echo '<li><span class="outside">' . $row['name'] . '</span><ul class="inside"';
    $i++;
    $previous_level = $data[0]['level'];
    foreach($data as $row) {
        if($row['level'] < $previous_level)
        {
            $difference = $previous_level - $row['level'];
            for($j=0;$j<$difference;$j++) {echo '</ul></li>'; $i--;}
        }
        if(!empty($tree->get_node_by_parent($row['id']))) {
            echo '<li><span class="outside">' . $row['name'] . '</span><ul class="inside"';
            $i++;
        }
    else echo '<li>' . $row['name'] . '</li>';
    $previous_level = $row['level'];
}}
else echo $row['name'];
for($j=0; $j<$i;$j++) echo '</ul></li>';
echo "</ul><br>";
echo '<button onclick="Tree()" id="button">Show/hide tree</button><br>';
echo '<a href ="admin.html">Admin</a>';
?>
<script>
    let toggler = document.getElementsByClassName("outside");
    let i;
    let button;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".inside").classList.toggle("active");
            this.classList.toggle("outside-down");
        });
    }
    function Tree() {
        for (i = 0; i < toggler.length; i++) {
            if(toggler[i].parentElement.querySelector(".inside").classList.contains("active"))
            {
                for (i = 0; i < toggler.length; i++){
                toggler[i].parentElement.querySelector(".inside").classList.remove("active");
                toggler[i].classList.remove("outside-down");}
            }
            else {
                for (i = 0; i < toggler.length; i++){
                toggler[i].parentElement.querySelector(".inside").classList.add("active");
                toggler[i].classList.add("outside-down");}
            }
        }
        }
</script>
</body>
</html>
