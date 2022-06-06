<?php 
require_once('connect.php');

function prepare_insert_query($table,$columns)
{
    $sql = "INSERT INTO $table (";
    $names = array_keys($columns);
    $val = implode(",",$names);
    $sql .= $val . ') VALUES (?'. str_repeat(',?',count($names)-1).')';
    return $sql;
}

function insert_link($columns,$number_of_seats)
{
    try {
        global $db;
        $db->beginTransaction();
        $insert_link = prepare_insert_query('link',$columns);
        $link = $db->prepare($insert_link);
        $link->execute(array_values($columns));

        $id_link = $db->lastInsertId();

        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        $seat = $db->prepare("INSERT INTO `seat` (`seat_id`, `reservation_id`, `link_id`, `price_category_id`) VALUES (?, ?, ?, ?)");
        for ($i = 1; $i <= $number_of_seats; $i++) {
            $seat->execute(array($i,NULL,$id_link,1));
        }
        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
    return $id_link;
}

function insert($table, $columns)
{
    $sql = prepare_insert_query($table,$columns);
    global $db;
    try {
        $sth = $db->prepare($sql);
        print_r($sth);
        $sth->execute(array_values($columns));
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
    return true;
}

function prepare_values_separated_by_sep($names,$sep)
{
    $last = end($names);
    $sql = '';
    foreach ($names as $name)
    {
        $sql .= $name . " =? ";
        if ($name != $last)
            $sql .= " $sep ";
    }
    return $sql;
}

function delete($table,$columns)
{
    print_r($columns);
    print_r(array_values($columns));
    global $db;
    $sql = "DELETE FROM `$table` WHERE ";
    $names = array_keys($columns);
    $sql .= prepare_values_separated_by_sep($names,"and");
    try {
        $sth = $db->prepare($sql);
        print_r($sth);
        print_r((array_values($columns)));
        $sth->execute(array_values($columns));
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
}



function update($table, $conditions,$new_values)
{
    global $db;
    $sql = "UPDATE $table SET ";
    $names = array_keys($new_values);
    $sql .= prepare_values_separated_by_sep($names,",");
    $sql .= ' WHERE ';
    $names = array_keys($conditions);
    $sql .= prepare_values_separated_by_sep($names,"and");
    $values = array_merge(array_values($new_values),array_values($conditions));
    
    try {
        $sth = $db->prepare($sql);
        $sth->execute($values);
    } catch (PDOException $e) {
        print_r($sth);
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
}

function approve($table,$id)
{
    global $db;
    try {
        $sql = "UPDATE $table SET status_id = '2' WHERE $table"."_id=$id";
        $result = $db->query($sql);
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
}

function approve_change_stop($row_id)
{
    try { 
        global $db;
        $db->beginTransaction();
        $stmt = $db->prepare("SELECT * FROM change_stop where change_stop_id=?");
        $stmt->execute(array($row_id));
        $row = $stmt->fetch();
        if (!$row)
        {
            $db->rollBack();
            $_SESSION['dbMsg'] = "Change was not found";
            die();    
        }
        $db->query("UPDATE stop SET position ='".$row['position']."' WHERE stop_id=".$row['stop_id']);
        $stmt = $db->prepare("DELETE FROM change_stop WHERE change_stop_id =?");
        $stmt->execute(array($row_id));
        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['dbMsg'] = $e->getMessage();
        die();    
    }
}


?>


