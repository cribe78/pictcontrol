<?php

// A library of controller functions
// ct = control_type
// cn = control_name
// tt = target_type
// tn = target_num 

include("sub/controldefs.php");
include("sub/targetdefs.php");


$id_idx = 1;

$log = array();


function alertControlDaemon($tt, $tn, $token = "POLL") {
    // Send a UDP message to a control daemon, alerting it to 
    // check the command queue for new commands
    global $mysqli;

    $loopcount = 0;
    $max_loops = 1;

    static $daemons = false;

    if (! $daemons) {
        $daemons = array();
        $select_daemon = $mysqli->prepare(
            "select target_type, target_num, port, pid from command_daemons");

        if (! $select_daemon) {
            pclog("prepare select daemon error: {$myslqi->error}");
            return;
        }

        if (! $select_daemon->execute()) {
            pclog("error selecting control daemon: {$select_daemon->error}");
            return;
        }

        $select_daemon->bind_result($dtt, $dtn, $port, $pid);
        $select_daemon->store_result();
        while ($select_daemon->fetch()) {
            if (! isset($daemons[$dtt])) {
                $daemons[$dtt] = array();
            }

            $daemons[$dtt][$dtn] = array("port" => $port, "pid" => $pid);
        }
    }


    if (isset($daemons[$tt][$tn])) {
        $port = $daemons[$tt][$tn]["port"];
        $pid = $daemons[$tt][$tn]["pid"];
        if ( $port == 0 ) {
            $ps = exec("ps $pid");
            if (preg_match("/pcontrol-daemon --tt=$tt --tn=$tn/", $ps)) {
                pclog("$tt:$tn daemon still starting... maybe next time");
                return;
            }
            else {
                pclog("process $pid not found, relaunching");
                launchControlDaemon($tt, $tn);
                return;
            }
        }

        $fp = stream_socket_client("udp://127.0.0.1:$port", $errno, $errstr);
        if (! $fp) {
            pclog("$tt:$tn could not connect to control daemon at port $port)");
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
        pclog("$tt:$tn NO ACK received (pid $pid)");

        if ($loopcount == $max_loops) {
            pclog("killing process $pid");
            exec("kill $pid");
        }
    }

    launchControlDaemon($tt, $tn);
}


function getCmdIdx() {
    global $mysqli;
    $add_idx = $mysqli->prepare(
        "insert into cmd_idxs () values ()");

    if (! $add_idx->execute()) {
        dwlog("insert cmd_idx error: $add_idx->error");
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
    global $target_ports;
    global $active_targets;

    if (! isset($active_targets[$tt][$tn])) {
        return;
    }

    $delete_daemon = $mysqli->prepare(
        "delete from command_daemons where 
            target_type = ?
            and target_num = ?");
    
    if (! $delete_daemon) {
        pclog("$tt:$tn delete_daemon prepare error: {$mysqli->error}");
        return;
    }

    $delete_daemon->bind_param('si', $tt, $tn);
    if (! $delete_daemon->execute()) {
        pclog("$tt:$tn delete_daemon execute error: {$delete_daemon->error}");
        return;
    }
    else {
        //pclog("killing running pcontrol-daemon processes");
        // exec("killall pcontrol-daemon");
    }

    if (isset($target_ports[$tt])) {
        if (isset($target_ports[$tt]['ip'][$tn])) {
            $ip = $target_ports[$tt]['ip'][$tn];
        }
        else {
            $ip = $target_ports[$tt]['ip'][1];
        }

        if (isset($target_ports[$tt]['port'][$tn])) {
            $port = $target_ports[$tt]['port'][$tn];
        }
        else {
            $port = $target_ports[$tt]['port'][1];
        }


        $exec_str = "./pcontrol-daemon --tt=$tt --tn=$tn "
            . " --address=$ip --port=$port";
        pclog("$tt:$tn launching pcontrol-daemon: $exec_str");
        exec($exec_str);
        pclog("$tt:$tn daemon launched");
    }
}

function numberControl($tt, $tn, $cn, $classes = "") {
    global $commands;
    $cmd = $commands[$tt][$cn];

    echo("<div class='numberctl $classes'>"); 
    echo("<span class='numberlabel'>{$cmd['name']}</span>");
    echo("<input type='number' class='pcontrol' data-tt='$tt'
            data-tn='$tn' data-cn='$cn' data-ut='number'
            min='{$cmd['min']}'  max='{$cmd['max']}' />");
    echo("<span class='numberrange'>({$cmd['min']} - {$cmd['max']})</span>");
    echo("</div>");
    

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
    

function pingControlDaemons() {
    global $active_targets;
    foreach ($active_targets as $tt => $tna) {
        foreach ($tna as $tn => $tv) {
            alertControlDaemon($tt, $tn, "ALV?");
        }
    }
}

function setPControl($tt, $tn, $ct, $cn, $value) {
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

function queueSlaveCommands($tt, $tn, $ct, $cn, $value) {
    global $mysqli;

    $fetch_slaves = $mysqli->prepare(
        "select s.target_type, s.target_num, s.command_type, s.command_name, s.value
            from slave_commands s
            inner join master_commands m
            on m.master_command_id = s.master_command_id
            where m.target_type = ?
            and m.target_num = ?
            and m.command_name = ?");

    if (! $fetch_slaves) {
        pclog("prepare fetch_slaves error: {$mysqli->error}");
        jsonResponse();
    }

    $fetch_slaves->bind_param('sis', $tt, $tn, $cn);

    if (! $fetch_slaves->execute()) {
        pclog("execute fetch_slaves error: {$mysqli->error}");
        jsonResponse();
    }

    $fetch_slaves->bind_result($stt, $stn, $sct, $scn, $svalue);
    $fetch_slaves->store_result();

    while ($fetch_slaves->fetch()) {
        $ett = $stt == NULL ? $tt : $stt;
        $etn = $stn == NULL ? $tn : $stn;
        $ect = $sct == NULL ? $ct : $sct;
        $ecn = $scn == NULL ? $cn : $scn;
        $evalue = $svalue == NULL ? $value : $svalue;

        queueCommand($ett, $etn, $ect, $ecn, $evalue);
    }
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






    

