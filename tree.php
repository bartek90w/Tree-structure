<?php

class Tree {
    protected $database;
    protected $table;

    public function __construct($table = 'category')
    {
        $this->table=$table;
        try
        {
            $host = 'localhost';
            $db_name = 'tree';
            $username = 'root';
            $password = '';
            $this->database = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
        }
		catch(PDOException $e)
        {
            exit($e->getMessage());
        }

    }

    public function add_node($parent_id, $name)
    {
        if(!$node = $this->get_node($parent_id)) return false;

        $query = 'INSERT INTO ' . $this->table . ' VALUES(NULL, :parent_id, :name)';
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
        $stmt->bindParam(':name',$name,PDO::PARAM_STR);
        $stmt->execute();
        return $this->database->lastInsertId();
    }

    public function get_node($id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_node_by_parent($parent_id)
    {
        $result = array();
        $query = 'SELECT * FROM ' . $this->table . ' WHERE pid = :id';
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':id', $parent_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function edit_node($id,$name)
    {
        if(!$node = $this->get_node($id)) return false;

        $query = 'UPDATE ' . $this->table . ' SET name = :name WHERE id = :id';
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':name',$name,PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function move_node($id,$parent_id)
    {
        if(!$node = $this->get_node($id)) return false;

        $query = 'UPDATE ' . $this->table . ' SET pid = :parent_id WHERE id = :id';
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':parent_id',$parent_id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete_node_with_children($id)
    {
        if(!$node = $this->get_node($id)) return false;

        $query = 'DELETE FROM '.$this->table.' WHERE id = :id';
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function delete_node_without_children($id)
    {
        if(!$node = $this->get_node($id)) return false;

        $parent_id = $node['pid'];
        $id = $node['id'];
        $nodes = $this->get_node_by_parent($id);
        foreach($nodes as $node)
        {
            $this->move_node($node['id'],$parent_id);
        }
        return $this->delete_node_with_children($id);

    }

    public function create_tree($root_name = 'root')
    {
        $this->database->exec($query = 'CREATE TABLE IF NOT EXISTS`'.$this->table.'` (
                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `pid` int(10) unsigned DEFAULT NULL,
                      `name` varchar(255) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `pid` (`pid`),
                      CONSTRAINT `category_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->database->exec('DELETE FROM ' . $this->table);
        $this->database->exec('ALTER TABLE ' . $this->table . ' AUTO_INCREMENT = 1');
        $query = 'INSERT INTO ' . $this->table . '(name) VALUES(:name)';

        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':name', $root_name);
        return $stmt->execute();
    }

    public function get_tree($parent_id = 1)
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->database->query($query);
        $result = array();
        if($stmt)
        {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                $result[$row['pid']][] = $row;
            return self::make_array($result,$parent_id,0);
        }
    }

    protected static function make_array($tree_array, $parent_id, $level)
    {
        $result = array();
        if(isset($tree_array[$parent_id]))
        {
            foreach($tree_array[$parent_id] as $row)
            {
                $row['level'] = $level;
                $result[] = $row;
                $result = array_merge($result,self::make_array($tree_array, $row['id'], $level+1));
            }
        }
        return $result;
    }
}
