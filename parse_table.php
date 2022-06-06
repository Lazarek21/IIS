<?php 

require_once("connect.php");

$ar = array(
    "stop_id"=>"position", 
    "carrier_id"=>"name", 
    "type_id"=>"description", 
    "price_category_id"=>"description", 
    "link_id"=>"link_id", 
    "reservation_id"=>"reservation_id",
    "user_id" => "email",
    "status_id" => "description"
);
$dbs = ["link", "user", "seat", "reservation", "stop"];


function parse_table_names($db, $dbTable){
    global $db;
    try{
        $q = $db->prepare("DESCRIBE ".$dbTable);
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
    if (($key = array_search("password", $table_fields)) !== false) {
        unset($table_fields[$key]);
    }
    return $table_fields;
}

function parse_table($dbTable, $sql){
    global $db, $dbs, $ar;
    try {
        $sth = $db->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
    $first = true;
    if (empty($result) && basename($_SERVER['PHP_SELF']) != 'approve.php')
    {
        echo "<h4 style='color:grey'> Žádné výsledky </h4>";
    } else if (empty($result)) {
        echo "<h4 style='color:grey'> Žádné výsledky </h4>";
    }

    foreach ($result as $row) {
        if ($first)
        {
            //print header and get column names
            $first = false;
            $table_fields = array_keys($row);
            if (($key = array_search("password", $table_fields)) !== false) {
                unset($table_fields[$key]);
            }
            print '<thead><tr>';
            foreach($table_fields as $field){
                print '<th>'.$field.'</th>';
            }
            print '</tr></thead><tbody>';
        }
        print '<tr class="tableTr">';
        foreach($table_fields as $field){
            print '<td>';
            if(in_array($dbTable, $dbs) && in_array($field, array_keys($ar)) && ($field != $table_fields[0])){
                $stops = get_object_vars(json_decode(parse_column(substr($field, 0, -3), $ar[$field])));            
                foreach(array_keys($stops) as $key){
                    if($result != [0] && $stops[$key] == $row[$field]){
                        print $key;
                    }
                }
            } else {
                print $row[$field];
            }
            print '</td>';
        }
        print '</tr>';
    }
    print '</tbody>';
}

function print_row($row){
    $hidden = $row[1];
    $row = $row[0];
    $select = FALSE;
    echo '<div class="horLayout">';
    echo '<input name="db" value="'.$_GET['db'].'" style="display: none;"></input>';
    echo '</div>';
    if(isset($_GET['rowPK'])){
        if ($_GET['db'] == 'link_stops'){
            echo '<div class="horLayout">';
            echo '<input name="link_id" value="'.$_GET['rowPK'].'" style="display: none;"></input>';
            echo '</div>';
        } else if ($_GET['db'] == 'stop' && $_GET['action'] == 'update' && $_SESSION['type_id'] == 4){
            echo '<div class="horLayout">';
            echo '<input name="cond[stop_id]" value="'.$_GET['rowPK'].'" style="display: none;"></input>';
            echo '</div>';
        } else if ($_GET['db'] == 'stop'){
            echo '<div class="horLayout">';
            echo '<input name="stop_id" value="'.$_GET['rowPK'].'" style="display: none;"></input>';
            echo '</div>';
        } else {
            echo '<div class="horLayout">';
            echo '<input name="cond['.$_GET['db'].'_id]" value="'.$_GET['rowPK'].'" style="display: none;"></input>';
            echo '</div>';
        }
    } 
    echo '<div class="horLayout">';
    echo '<input name="action" value="'.$_GET['action'].'" style="display: none;"></input>';
    echo '</div>';
    foreach($hidden as $hidden_key => $hidden_value){
        echo '<div class="horLayout">';
        echo '<input name="'.$hidden_key.'" value="'.$hidden_value.'" style="display: none;"></input>';    
        echo '</div>';
    }
    foreach($row as $key => $value){
        echo '<div class="horLayout">';     
        echo '<label for="'.$key.'" style="text-align:right;padding-right:10px;">'.$key.'</label>';
        if (gettype($value) == "array"){
            echo '<select name="'.$key.'">';
            
            foreach($value as $key1 => $value1){
                echo '<option value="'.$key1.'" ';
                if(count($value1)>1){
                    $select = TRUE;
                    echo 'selected ';
                }
                echo '>'.$value1[0].'</option>';
            }
            echo '<option value="" ';
            if (!$select){
                echo 'selected';
            }
            echo '></option>';
            echo '</select>';
        } else {
            if($key == "time"){
                echo '<input type="time" name="time" value="'.$value.'" class="newInput" step="1" required></input>';
            } else if ($key == "number_of_seats"){ 
                echo '<input type="number" name="'.$key.'" value="'.$value.'" class="newInput"></input>';
            } else {
                echo '<input type="text" name="'.$key.'" value="'.$value.'" class="newInput"></input>';
            }
        }
        echo '</div>';
    }
}

function parse_stop_row($tableRow){
    global $db;
    $position = "";
    $parsed_row = array();
    
    if($tableRow != ""){
        $sql = 'SELECT position FROM stop WHERE stop_id =?';

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute(array($tableRow));
            $result = $stmt->fetch();
            if (!$result)
            {
                $_SESSION['dbMsg'] = "Row with ID $tableRow in table stop not found";
                header('Location: '.$_SESSION['lastRefer']);
                die();
            }
            $position = $result['position'];
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
    }
    $parsed_row['position'] = $position;

    $hidden_row = [];
    if($_SESSION['type_id'] == 4){
        $parsed_row = array_merge($parsed_row, $hidden_row);
        $hidden_row["status_id"] = 2;
    }
    return [$parsed_row, $hidden_row];
}

function parse_user_row($tableRow){
    global $db;
    $email = "";
    $type_id = "";
    $carrier_id = "";
    $parsed_row = array();
    $found = FALSE;
   
    if($tableRow != ""){
        $sql = 'SELECT email, carrier_id, type_id FROM user WHERE user_id = '.$tableRow;
        try {
            $result = $db->query($sql);
            $email = $result->fetch();
            if (!$email)
            {
                $_SESSION['dbMsg'] = "Row with ID $tableRow in table user not found";
                header('Location: '.$_SESSION['lastRefer']);
                die();
            }
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
        $type_id = $email['type_id'];
        $carrier_id = $email['carrier_id'];
        $email = $email['email'];
    }
    $parsed_row['email'] = $email;
    
    if($_SESSION['type_id'] == 3){
    $hidden_row = [
        'type_id' => $_SESSION['type_id']-1, 
        'carrier_id' => $_SESSION['carrier_id']
    ];
    } else {
        $sql = 'SELECT carrier_id, name FROM carrier';
        try {
            $result = $db->query($sql);
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
        foreach($result as $stop_arr){   
            if($carrier_id == $stop_arr["carrier_id"]){
                $hidden_row['carrier_id'][$stop_arr["carrier_id"]] = [$stop_arr["name"], 1];
                $found = TRUE;
            } else {
                $hidden_row['carrier_id'][$stop_arr["carrier_id"]] = [$stop_arr["name"]];
            }
        }
        if(!$found){
            $hidden_row['carrier_id'][""] = ["", 1];
        }
        $sql = 'SELECT type_id, description FROM type';
        try {
            $result = $db->query($sql);
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
        foreach($result as $stop_arr){   
            if($type_id == $stop_arr["type_id"]){
                $hidden_row['type_id'][$stop_arr["type_id"]] = [$stop_arr["description"], 1];
            } else {
                $hidden_row['type_id'][$stop_arr["type_id"]] = [$stop_arr["description"]];
            }
        }
    }

    if($_SESSION['type_id'] == 4){
        $parsed_row = array_merge($parsed_row, $hidden_row);
        $hidden_row = [];
    }

    return [$parsed_row, $hidden_row];
}

function parse_reservation_row($tableRow){
    global $db;
    $email = "";
    $parsed_row = array();
   
    if($tableRow != ""){
        $sql = 'SELECT email FROM user WHERE user_id = '.$tableRow;
        try {
            $result = $db->query($sql)->fetch();
            if (!$result)
            {
                $_SESSION['dbMsg'] = "Row with ID $tableRow in table reservation not found";
                header('Location: '.$_SESSION['lastRefer']);
                die();
            }
            $email = $result['email'];
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
    }
    $parsed_row['email'] = $email;
    
    $hidden_row = [
        'type_id' => $_SESSION['type_id']-1, 
        'carrier_id' => $_SESSION['carrier_id']
    ];

    if($_SESSION['type_id'] == 4){
        $parsed_row = array_merge($parsed_row, $hidden_row);
        $hidden_row = [];
    }

    return [$parsed_row, $hidden_row];
}

function parse_link_row($tableRow){
    global $db;
    $description = "";
    $stop_id = "";
    $parsed_row = array();
    
    if($tableRow != ""){
        $sql = 'SELECT description, stop_id FROM link WHERE link_id = '.$tableRow;
        try {
            $result = $db->query($sql)->fetch();
            if (!$result)
            {
                $_SESSION['dbMsg'] = "Row with ID $tableRow in table link not found";
                header('Location: '.$_SESSION['lastRefer']);
                die();
            }
            $description = $result['description'];     
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
    } else {
        $parsed_row["number_of_seats"] = 50;
    }
    $parsed_row['description'] = $description;

    if($_SESSION["type_id"] == 4){
        $sql3 = 'SELECT carrier_id, name FROM carrier';
        try {
            $result3 = $db->query($sql3);
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
        foreach($result3 as $stop_arr){   
            $hidden_row['carrier_id'][$stop_arr["carrier_id"]] = [$stop_arr["name"]];
        }
    } else {
        $hidden_row = ['carrier_id' => $_SESSION['carrier_id']];
    }
    if($_SESSION['type_id'] == 4){
        $parsed_row = array_merge($parsed_row, $hidden_row);
        $hidden_row = [];
    }
    return [$parsed_row, $hidden_row];
}

function parse_carrier_row($tableRow){
    global $db;
    $name = "";
    
    if($tableRow != ""){
        try {
        $sql = 'SELECT name FROM carrier WHERE carrier_id = '.$tableRow;
        $result = $db->query($sql)->fetch();
        if (!$result)
        {
            $_SESSION['dbMsg'] = "Row with ID $tableRow in table carrier not found";
            header('Location: '.$_SESSION['lastRefer']);
            die();
        }
        $name = $result['name'];
        } catch (PDOException $e){
            $_SESSION['dbMsg'] = $e->getMessage();
            header('Location: '.$_SESSION['lastRefer']);
            die();
        }
    }
    $parsed_row['name'] = $name;
    
    $hidden_row = [];
    if($_SESSION['type_id'] == 4){
        $parsed_row = array_merge($parsed_row, $hidden_row);
        $hidden_row = [];
    }
    return [$parsed_row, $hidden_row];
}

function parse_link_stops_row($tableRow){
    global $db;
    $sql = 'SELECT stop_id,position FROM stop WHERE status_id = 2';
    try {
        $result = $db->query($sql);
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
    foreach ($result as $row)
    {
        $parsed_row['stop_id'][$row["stop_id"]] = [$row["position"]];
    }
    $parsed_row['time'] = '';
    $hidden_row = ["link_id" => $tableRow];
    return [$parsed_row, $hidden_row];
}

function parse_row($dbTable, $tableRow){
    global $db;
    $table_fields = parse_table_names($db, $dbTable);

    global $ar;

    global $dbs;
    $result = [0];
    if($tableRow != NULL){
        $sql = 'SELECT * FROM '.$dbTable.' WHERE '.$table_fields[0].' = '.$tableRow;
        try {
            $result = $db->query($sql);
        } catch (PDOException $e) {
            $_SESSION['dbMsg'] = $e->getMessage();
            die();
        }
    }

    $skip = FALSE;
    switch($dbTable){
        case "stop":
            $row = parse_stop_row($tableRow);
            break;
        case "user":
            $row = parse_user_row($tableRow);
            break;
        case "link":
            $row = parse_link_row($tableRow);
            break;
        case "carrier":
            $row = parse_carrier_row($tableRow);
            break;
        case "reservation":
            $row = parse_reservation_row($tableRow);
            break;
        case "link_stops":
            $row = parse_link_stops_row($tableRow);
            break;
        default:
        $skip = TRUE;
        break;
    }
    if(!$skip)
        print_row($row);
    if($_SESSION['type_id'] == 4 && $skip) {
        foreach ($result as $row) {
            foreach($table_fields as $field){
                echo '<div class="horLayout">';
                echo '<label for="'.$field.'" style="text-align:right;padding-right:10px;">'.$field.'</label>';
                if(in_array($dbTable, $dbs) && in_array($field, array_keys($ar)) && ($field != $table_fields[0])){
                    echo '<div style="table-cell">';
                    echo '<select>';

                    echo '<option value=""></option>';
                    $stops = get_object_vars(json_decode(parse_column(substr($field, 0, -3), $ar[$field])));

                    foreach(array_keys($stops) as $key){
                        echo '<option value="'.$stops[$key].'"';
                        if($result != [0] && $stops[$key] == $row[$field]){
                            echo 'selected';
                        }
                        echo '>'.$key.'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }else{
                    echo '<input type="text" name="'.$field.'"';
                    if($tableRow != NULL) {
                        echo 'value="'.$row[$field].'"';
                    }
                    echo' class="newInput"';
                    if ($field === $table_fields[0]) {
                        echo 'readonly';
                    }
                    echo '></input>';
                }
                echo '</div>';
            }
        }
    }
}

function parse_column($dbTable, $tableColumn){
    global $db;
    $table_fields = parse_table_names($db, $dbTable);
    $sql = 'SELECT * FROM '.$dbTable;
    try {
        $result = $db->query($sql);
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }

    $list = array();
    foreach ($result as $column)
        $list[$column[$tableColumn]] = $column[$dbTable."_id"];

    return json_encode($list, JSON_FORCE_OBJECT);
}

function get_stops($linkPK){
    global $db;

    $sql = 'SELECT * FROM '.$dbTable;
    $result = $db->query($sql);


    $list = array();
    foreach ($result as $column)
        $list[$column[$tableColumn]] = $column["stop_id"];

    return json_encode($list);
}

function print_stops(){
    global $db;
    try {
        $sql = 'SELECT * FROM link_stops INNER JOIN stop on link_stops.stop_id = stop.stop_id WHERE link_stops.link_id = '.$_SESSION['link_id'].' GROUP BY (link_stops.stop_id) ORDER BY time DESC';
        $result = $db->query($sql)->fetchAll();
        $sql = 'SELECT stop_id FROM link WHERE link_id = '.$_SESSION['link_id'];
        $result2 = $db->query($sql)->fetch();
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        header("Location: index.php");
        die();
    }

    if (!$result)
    {
        $_SESSION['dbMsg'] = "Link with id ".$_SESSION['link_id']. " hasnt got any stops";
        header("Location: ".$_SERVER['HTTP_REFERER']);
        die();
    }
    $stop_id = $result2['stop_id'];
    $list = [];
    foreach ($result as $row) {
        $list[$row['stop_id']] = $row['position'];
    }
    
    echo '<div style="display:table;margin-left:auto;margin-right:auto;">';
    foreach (array_keys($list) as $stop){ 
        echo '  <div style="display:table-row">';
        echo '      <div style="display:table-cell;text-align: center;">';
        echo '|';
        echo '      </div>';
        echo '  </div>';
        echo '  <div class="stopRow" style="display:table-row">';
        echo '      <div class="stopCell';
        if ($stop_id == $stop){
            echo ' selected ';
        }
        echo '" id="'.$stop.'" style="text-align:center">';
        echo $list[$stop];
        echo '      </div>';
        echo '  </div>';
        

    }
    echo '</div>';

}

function print_links($stops){
    $pos = "";
    require_once("connect.php");
    global $db;
    if($stops == NULL){
        echo '<h2 style="margin:auto: width:100px">Žádný výsledek</h2>';
    } else {
        echo '<div id="linkTable" style="display:table;margin-top:100px;margin-left:auto;margin-right:auto;width:100%">';
        foreach($stops as $stop){
            echo '<div style="display:table-row">';
            echo '  <div display="display:table-cell;">';
            echo '      <div class="linkButton">';
            echo '          <div style="float:left">';
            echo '          <h4>Linka<div class="linkPK">'.$stop["link_id"].'</div></h4>';
            
            try {
                $q = $db->prepare("SELECT position FROM stop WHERE stop_id=?");
                $q->execute(array($stop["position"]));
                
                if($q){
                    $pos = $q->fetch()['position'];
                }
            } catch (PDOException $e) {
        
            }

            echo '          <i style="text-align:left;padding: 15px 15px;color:#33ff33">'.$pos.'</i>';
            echo '          </div>';
            echo '          <div style="text-align:left;position:absolute;left:50%;top:50%;transform: translate(-50%, -50%);">';
            echo '              <b>'.$stop["from"][0].'</b> '.$stop["from"][1].'<br><div style="text-align:center">⋮</div>';
            echo '              <b>'.$stop["whereto"][0].'</b> '.$stop["whereto"][1].'<br>';
            echo '          </div>';
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        }
        echo '</div>';
    }
}

function update_state_seat($link_id){
    global $db;
    $_SESSION['link_id'] = $link_id;

    $sql = 'SELECT * FROM seat WHERE link_id = \''.$link_id.'\'';
    try {
        $result = $db->query($sql);
    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }
    $seats_id = array();
    $reservation_id = array();
    $pc_id = array();

    $i = 0;
    foreach ($result as $row) {
        $seats_id[$i] = $row['seat_id'];
        $reservation_id[$i] = $row['reservation_id'];
        $pc_id[$i] = $row['price_category_id'];
        $i++;
    }

    $row_count = intdiv($i, 4);
    $rem_seat = ($i)%4;

    for($j = 0; $j < $row_count; $j++){
        echo '<div class="row">';
        for($k = 0; $k < 4; $k++){
            if($reservation_id[$j*4+$k]){
                echo '<div class="seat occupied pc'.$pc_id[$j*4+$k].'"><b>';
                echo $j*4+$k+1;
                echo '</b></div>';
            } else {
                echo '<div class="seat pc'.$pc_id[$j*4+$k].'"><b>';
                echo $j*4+$k+1;
                echo '</b></div>';
            }
        }
        echo '</div>';
    }
    
    if ($rem_seat > 0){
        echo '<div class="row last">';
        for($j = $i - $rem_seat; $j < $i; $j++){
            if($reservation_id[$j]){
                echo '<div class="seat last occupied pc'.$pc_id[$j*4+$k].'"><b>';
                echo $j+1;
                echo '</b></div>';
            } else {
                echo '<div class="seat last pc'.$pc_id[$j].'"><b>';
                echo $j+1;
                echo '</b></div>';
            }    
        }
        echo '</div>';
    }
}

function find_connections($from, $where_to, $time)
{
    global $db;
    $output = NULL;
    try {
        $sql = "SELECT time,link.description,stop.position as stop_position,link.stop_id as link_position,link.link_id from link_stops as ls,link,stop where ls.stop_id=? and ls.time>=? and link.link_id=ls.link_id and ls.stop_id = stop.stop_id and exists(select* from link_stops as ls1 where ls1.stop_id=? and ls.time < ls1.time and ls.link_id = ls1.link_id) order by time,link_id;";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($from, $time, $where_to));
        $result = $stmt->fetchAll();
        
        foreach ($result as $row) {     
            $dest = "SELECT time,stop.position as stop_position from link_stops,stop WHERE link_stops.stop_id = stop.stop_id  and link_id='".$row['link_id']."' and time>'".$row['time']."'and link_stops.stop_id=? ORDER BY time";
            $stmt = $db->prepare($dest);
            $stmt->execute(array($where_to));

            $res = $stmt->fetch();
            $output[]=array("link_id" => $row["link_id"], "from" => array($row["time"],$row["stop_position"]),"whereto" => array($res['time'],$res["stop_position"]),"position" => $row["link_position"])  ;
        }

    } catch (PDOException $e) {
        $_SESSION['dbMsg'] = $e->getMessage();
        die();
    }

    return $output;
}

?>