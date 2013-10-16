<?php

// A library of controller functions
// ct = control_type
// cn = control_name
// tt = target_type
// tn = target_num 

include("sub/controldefs.php");

$screen_list = array(1, 2, 3, 4, 5);
$screen_names = array( 1 => "Far Left",
                       2 => "Left",
                       3 => "Center",
                       4 => "Right",
                       5 => "Far Right" );
$screen_icons = array( 1 => "screen1.png",
                       2 => "screen2.png",
                       3 => "screen3.png",
                       4 => "screen4.png",
                       5 => "screen5.png");
$projector_ip = array( 1 => "192.168.1.100",
                       2 => "192.168.1.101",
                       3 => "192.168.1.102",
                       4 => "192.168.1.103",
                       5 => "192.168.1.104");
$projector_port = 1025;


$scaler_ip = array(    1 => "192.168.1.200",
                       2 => "192.168.1.201",
                       3 => "192.168.1.202",
                       4 => "192.168.1.203",
                       5 => "192.168.1.204");
$scaler_port = 30001;

$audio_onkyo_ip = "10.5.162.232";
$audio_onkyo_port = 4001;

$switcher_dxp_ip = "10.5.162.202";
$switcher_dxp_port = "23";

$id_idx = 1;

$log = array();

function addCommand($tt, $tn, $ct, $cn, $val) {
    global $mysqli;
    $add_command = $mysqli->prepare(
        "insert ignore into commands
            (target_type, target_num, command_type, command_name, value)
            value (?, ?, ?, ?, ?)");

    if (! $add_command) {
        pclog("prepare add_command error: {$mysqli->error}");
        return;
    }

    $add_command->bind_param('sssss', $tt, $tn, $ct, $cn, $val);

    if (! $add_command->execute()) {
        pclog("add_command execute failed: {$mysqli->error}");
    }
}


function alertControlDaemon($tt, $tn, $token = "POLL") {
    // Send a UDP message to a control daemon, alerting it to 
    // check the command queue for new commands
    global $mysqli;

    $loopcount = 0;
    $max_loops = 5;

    while ($loopcount <= $max_loops) {
        $loopcount++;
        //pclog("alertControlDaemon loop $loopcount");

        $select_daemon = $mysqli->prepare(
            "select port, pid from command_daemons
                where target_type = ?
                and target_num = ?");
        
        if (! $select_daemon) {
            pclog("prepare select daemon error: {$myslqi->error}");
            return;
        }

        $select_daemon->bind_param('si', $tt, $tn);

        if (! $select_daemon->execute()) {
            pclog("error selecting control daemon: {$select_daemon->error}");
            return;
        }

        $select_daemon->bind_result($port, $pid);
        $select_daemon->store_result();
        if ($select_daemon->fetch()) {
            if ( $port == 0 ) {
                pclog("daemon still starting... waiting");
                usleep(500000);
                continue;
            }

            $fp = stream_socket_client("udp://127.0.0.1:$port", $errno, $errstr);
            if (! $fp) {
                pclog("could not connect to control daemon ($tt/$tn/$port)");
                launchControlDaemon($tt, $tn);
                return;
            }    
            stream_set_timeout($fp, 2); 

            fwrite($fp, $token);
            //pclog("$token sent to control daemon");
            $pollresp = fread($fp, 3);
            
            if ($pollresp == "ACK") {
                fclose($fp);
                //pclog("ACK received from control daemon");
                return;
            }
            pclog("NO ACK received from $tt:$tn (pid $pid)");

            if ($loopcount == 5) {
                pclog("killing process $pid");
                exec("kill $pid");
            }
        }
        else {
            pclog("alertControlDaemon: no daemon registered");
        }
    }
    
    launchControlDaemon($tt, $tn);
}


function getCmdIdx() {
    global $mysqli;
    $add_idx = $mysqli->prepare(
        "insert into cmd_idxs () values ()");

    if (! $add_idx->execute()) {
        dwlog("insert cmd_idx error: $DBI::errstr");
        return 0;
    }

    return $mysqli->insert_id;
}


function getID() {
    global $id_idx;
    $id = "id$id_idx";
    $id_idx++;

    return $id;
}


function jsonResponse($response = "") {
    // By convention, use the global array $resp
    // to hold the json response
    global $resp;

    if (! $response) {
        $response = $resp;
    }

    header('Content-type: application/json');
    echo(json_encode($response));
    exit();
}

function launchControlDaemon($tt, $tn) {
    global $mysqli;
    global $audio_onkyo_ip;
    global $audio_onkyo_port;
    global $switcher_dxp_ip;
    global $switcher_dxp_port;
    global $projector_ip;
    global $projector_port;
    global $scaler_ip;
    global $scaler_port;


    $delete_daemon = $mysqli->prepare(
        "delete from command_daemons where 
            target_type = ?
            and target_num = ?");
    
    if (! $delete_daemon) {
        pclog("delete_daemon prepare error: {$mysqli->error}");
        return;
    }

    $delete_daemon->bind_param('si', $tt, $tn);
    if (! $delete_daemon->execute()) {
        pclog("delete_daemon execute error: {$delete_daemon->error}");
        return;
    }
    else {
        //pclog("killing running pcontrol-daemon processes");
        // exec("killall pcontrol-daemon");
    }
    
    if (preg_match("/proj|scaler|switcher-dxp|audio-onkyo/", $tt) &&
            preg_match("/\d/", $tn)) {
        $ip = "";
        $port = "";
        if ($tt == "audio-onkyo") {
            $ip = $audio_onkyo_ip;
            $port = $audio_onkyo_port;
        }
        elseif ($tt == "switcher-dxp") {
            $ip = $switcher_dxp_ip;
            $port = $switcher_dxp_port;
        }
        elseif ($tt == "proj") {
            $ip = $projector_ip[$tn];
            $port = $projector_port;
        }
        elseif ($tt == "scaler") {
            $ip = $scaler_ip[$tn];
            $port = $scaler_port;
        }

        $exec_str = "./pcontrol-daemon --tt=$tt --tn=$tn "
                . " --address=$ip --port=$port";
        pclog("launching pcontrol-daemon: $exec_str");
        exec($exec_str);
        pclog("daemon launched");        
    }
}



$insert_log = '';

function pclog($msg) {
    global $log;
    global $insert_log;
    global $mysqli;

    if (! $insert_log) {
        $insert_log = $mysqli->prepare(
            "insert into log (log_str) 
                values (?) ");

        if (! $insert_log) 
            error_log("prepare insert_log error: {$mysqli->error}");
    }
    
    $ts = date("Y-m-d H:i:s",time());
    $db_msg = "$ts $msg";
    $log[] = $db_msg;
    error_log($msg);
    
    $insert_log->bind_param('s', $db_msg);
    
    if(! $insert_log->execute())
        error_log("execute insert_log error: {$mysqli->error}");
}
    

function setPControl($tt, $tn, $ct, $cn, $value) {
    addCommand($tt, $tn, $ct, $cn, $value);
    
    if ($tt == 'proj' || 
        $tt == 'audio-onkyo' ||
        $tt == 'switcher-dxp' ||
        $tt == 'scaler' ) {
        queueCommand($tt, $tn, $ct, $cn, $value);
        alertControlDaemon($tt, $tn);
    }
    elseif ($tt === 'delay') {
        usleep($value);
    }
    else {
        pclog("setPControl: unhandled tt: $tt");
    }

}

function queueCommand($tt, $tn, $ct, $cn, $value) {
    global $mysqli;

    $cmd_idx = getCmdIdx();
    $queue_command = $mysqli->prepare(
        "insert into command_queue
        (target_type, target_num, command_type, command_name, 
        value, status, cmd_idx)
            value (?, ?, ?, ?, ?, 'QUEUED', ?)");

    $queue_command->bind_param('sisssi', $tt, $tn, $ct, $cn, $value, $cmd_idx);
    if (! $queue_command->execute()) 
        error_log("execute queue command error: {$mysqli->error}");

    
    pclog("command queued: $tt, $tn, $ct, $cn, $value, $cmd_idx");
}

function radioBar($tt, $tn, $cn, $values = NULL) {
    global $commands;
    $id = getID();
    $label = $commands[$tt][$cn]['name'];

    echo("<div class='pinputdiv ui-widget-header ui-corner-all'>
            <div class='ptoolbarlabel'>$label</div>
            <span id='$id' class='pcontrol'  data-ut='radio' 
                data-cn='$cn' data-tt='$tt' data-tn='$tn'>");

    if (is_null($values)) {
        $values = $commands[$tt][$cn]['values'];
    }

    foreach ($values as $key => $value) {
        radioHTML($id, $key, $value);
    }

    echo("</span></div>");
}

function radioHTML($set_id, $value, $label) {
    $id = getID();

    echo("<input type='radio' name='$set_id' id='$id'
            class='source-opt' data-value='$value' />
                <label for='$id'>$label</label>");

}


function sliderCombo($tt, $tn, $cn) {
    echo( "<span class='plabel' data-tt='$tt' data-cn='$cn'></span>
           <input class='pcontrol' data-tt='$tt' data-tn='$tn' 
           data-cn='$cn' data-ut='spinner' /> 
           <div class='pcontrol' data-tt='$tt' data-tn='$tn' 
             data-ut='slider'   data-cn='$cn'> </div>" );
}






    

