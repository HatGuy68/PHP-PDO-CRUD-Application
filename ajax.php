<?php

$action = $_REQUEST['action'];

if(!empty($action)) {
    require_once('includes/Player.class.php');
    $obj = new Player();

}

if ($action=='adduser' && !empty($_POST)) {
    $player_name = $_POST['player_name'];
    $player_score = $_POST['score'];
    $player_id = (!empty($_POST['userid'])) ? $_POST['userid'] : "";

    $player_data = [
        'player_name' => $player_name,
        'player_score' => $player_score
    ];

    if ($player_id) {
        $obj->update($player_data, $player_id);
    } else {
        $player_id = $obj->add_player($player_data);
    }

    if(!empty($player_id)) {
        $player = $obj->getRow('player_id', $player_id);
        echo json_encode($player);
        exit();
    }
}

if ($action=='getusers') {
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 4;
    $start = ($page-1)*$limit;

    $players = $obj->getRows($start, $limit);
    if (!empty($players)) {
        $players_list = $players;
    } else {
        $players_list = [];
    }
    $total = $obj->getCount();
    $playerArr = ['count'=>$total, 'players'=>$players_list];
    echo json_encode($playerArr);
}

if ($action == "getuser") {
    $player_id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    if (!empty($player_id)) {
        $player = $obj->getRow('player_id', $player_id);
        echo json_encode($player);
        exit();
    }
}

if ($action == "deleteuser") {
    $player_id = (!empty($_GET['id'])) ? $_GET['id'] : '';
    if (!empty($player_id)) {
        $isDeleted = $obj->deleteRow($player_id);
        if ($isDeleted) {
            $message = ['deleted' => 1];
        } else {
            $message = ['deleted' => 0];
        }
        echo json_encode($message);
        exit();
    }
}

?>