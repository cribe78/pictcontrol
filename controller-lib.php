<?php

// A library of controller functions
// ct = control_type
// cn = control_name
// tt = target_type
// tn = target_num 

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


$scaler_cnx = array ( 1 => '',
                      2 => '',
                      3 => '',
                      4 => '',
                      5 => '');
$proj_cnx = array ( 1 => '',
                      2 => '',
                      3 => '',
                      4 => '',
                      5 => '');

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






function f32Command($projector, $command, $value) {
    global $projector_ip;
    global $proj_cnx;
    $p = $projector;

    //pclog("f32cmd start $p:$command");
        
    if (! isset($projector_ip[$projector])) {
        error_log("f32command: invalid projector number: $projector");
       return 0; 
    }

    $fp = $proj_cnx[$projector];
    if (! $fp) {
        $fp = fsockopen($projector_ip[$projector], 1025, $errno, $errstr);
        if (! $fp) {
            // TODO: Handle "No route to host"
            error_log("f32command: $errstr");
            return null;
        }
        stream_set_timeout($fp, 3);

        if ($proj_cnx[$p] === 0) {
            fwrite($fp,"\r");
            $flush = fread($fp, 20);
            pclog("f32cmd $p:$command $flush flushed");
        }

        pclog("f32cmd $p:$command stream opened");

        $proj_cnx[$projector] = $fp;
    }
    else {
        usleep(10000);
    }

    $raw_command = ":" . $command .  $value . "\r\r\n";
    fwrite($fp, $raw_command);

    $starttime = time();
    $max_time = 20;
    $loopcount = 0;
    $response = 0;
    
    while (time() - $starttime < $max_time) {
        $per = '';
        $percount = 0;
        while ($per != '%' && $percount < 12) {
            $perstart = time();
            $per = fread($fp,1);
            //pclog("f32cmd $p:$command percount=$percount($per)");
            if (time() - $perstart > 1) 
                break;

            $percount++;
        }
        if ($per != '%') 
            break;

        $ret = fread($fp, 9);

        if (strlen($ret) < 9) {
            pclog("f32cmd $p:$command waiting for output ($ret)");
            usleep(200000);
            $ret .= fread($fp, 9 - strlen($ret));

            if (strlen($ret) == 0) 
                break;
        }

        if ($ret != "001 $command ") {
            pclog("f32cmd $p:$command invalid response header: $ret");
            usleep(200000);
        }
        else {
            $response = 1;
            break;
        }

        $loopcount++;
    }

    if (! $response) {
        pclog("f32cmd $p:$command aborting");
        fclose($fp);
        $proj_cnx[$p] = 0;
        return null;
    }

    $ret = fread($fp, 6);
    
    if ($ret == "e00001") {
        $ret_char = fread($fp, 1);
        $ret_str = "";
        while ($ret_char != "\r" && strlen($ret_char)) {
            $ret_str .= $ret_char;
            $ret_char = fread($fp,1);
        } 

        //fclose($fp);
        pclog("f32cmd $p:$command = $ret");
        return $ret_str;
    }

    //fclose($fp);
    if ($ret == "!00001") {
        error_log("f32 command: access denied");
        return null;
    }
    elseif ($ret == "!00002") {
        error_log("f32 command: cmd not available");
        return null;
    }
    elseif ($ret == "!00003") {
        error_log("f32 command: cmd not implemented");
        return null;
    }
    elseif ($ret == "!00004") {
        error_log("f32 command: value out of range");
        return null;
    }
    elseif ($ret === "000000") {
        pclog("f32cmd $p:$command = $ret");
        return(0);
    }
    else {
        $ret = preg_replace("/(^-?)(0+)(\d+)/", "$1$3", $ret);
        pclog("f32cmd $p:$command = $ret");
        return($ret);
    }
}

function f32CommandAll($command, $value) {
    global $screen_list;
    $projectors = array();
    
    foreach ($screen_list as $s) {
        $projectors[$s][$command] = f32command($s,$command, $value);
    }

    return($projectors);
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

function getPControl($tt, $tn, $ct, $cn) {
    if ($tt === 'proj') {
        return getProjectorControl($tn, $ct, $cn);
    }
    elseif ($tt === 'scaler') {
        return getScalerControl($tn, $ct, $cn);
    }
    else {
        pclog("getPControl: unhandled tt: $tt");
    }
}

function getProjectorControl($tn, $ct, $cn) {
    $ret = array("tt" => "proj",
                 "tn" => $tn,
                 "ct" => $ct,
                 "cn" => $cn);
    
    // Handle special cases
    
    // Handle standard controls
    if ($ct === 'boolean' || 
        $ct === 'range' ||
        $ct === 'select' ) {
        $ret['value'] = f32Command($tn, $cn, "?");
        //pclog("setControl: $cn=" . $ret['value']);
        return $ret;
    }
}

function getScalerControl($tn, $ct, $cn) {
    $ret = array( "tt" => "scaler",
                  "tn" => $tn,
                  "ct" => $ct,
                  "cn" => $cn);

    if ($ct == 'writeonly') {
        $ret['value'] = getScalerValue($tn, $cn);
        return $ret;
    }
    elseif ($cn == 'test') {
        $ret['value'] = getScalerValue($tn,'test');
        return $ret;
    }
    
    //
    elseif ($ct == 'range' || $ct == 'select' || $ct == 'boolean') {
        $resp = ia200Command($tn, "R$cn");
        //pclog("getSC: resp=$resp");
        $ret['value']  = $resp;
        return $ret;
    }

}


function getScalerValue($scaler, $field) {
    global $mysqli;
    $get_source = $mysqli->prepare("select value from scaler_values
                                where scaler = ?
                                and field = ?");

    if (! $get_source) 
        pclog("prepare get_source error: {$mysqli->error}");

    $get_source->bind_param('ss', $scaler, $field);
    $get_source->bind_result($value);

    if(! $get_source->execute())
        pclog("execute get_source error: {$mysqli->error}");
    
    $get_source->store_result();
    if ($get_source->fetch()) {
        return $value;
    }
}

function getID() {
    global $id_idx;
    $id = "id$id_idx";
    $id_idx++;

    return $id;
}

function ia200Command($screen, $command) {
    global $scaler_ip;
    global $scaler_cnx;

    if (! isset($scaler_ip[$screen])) {
        error_log("ia200command: invalid screen number: $screen");
       return null; 
    }

    $fp = $scaler_cnx[$screen];
    if (! $fp) {
        $fp = fsockopen($scaler_ip[$screen], 30001, $errno, $errstr);
        $scaler_cnx[$screen] = $fp;
    }

    if (! $fp) {
        pclog("ia200cmd $screen:$command $errstr");
        return null;
    }

    //error_log("ia200cmd($screen) $command"); 
    $raw_command = "A00" . $command . "\n";
    fwrite($fp, $raw_command);  

    $resp = fread($fp, 1);
    if ( $resp === "K" ) {
        pclog("ia200cmd $screen:$command K");
        return(1);
    }
    elseif ( $resp === "E" ) {
        pclog("ia200cmd($screen) error");
        return(0);
    }
    elseif ( $resp === "V" ) {
        $resp = fread($fp, 4);
        pclog("ia200cmd($screen): $command => $resp");
        $resp = preg_replace("/(^0*)(-*\d+)/","$2",$resp);
        if ($resp === '') { 
            $resp = 0;
        }
        return($resp);
    }
    elseif( $resp === "v") {
        $resp = "";
        $str_resp = "";    
        while ($resp != "\r") {
            $str_resp .= $resp;
            $resp = fread($fp, 1);
        }
        pclog("ia200cmd $screen:$command = $str_resp");
        return($str_resp);
    }

    pclog("ia200Command: no reposnse");
    //fclose($fp);
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


        $exec_str = "/home/chris/code/controller/pcontrol-daemon --tt=$tt --tn=$tn "
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
        $tt == 'switcher-dxp' ) {
        queueCommand($tt, $tn, $ct, $cn, $value);
        alertControlDameon($tt, $tn);
    }
    elseif ($tt === 'scaler') {
        return setScalerControl($tn, $ct, $cn, $value);
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


function setProjectorControl($tn, $ct, $cn, $value) {
    $ret = array("tt" => "proj",
                 "tn" => $tn,
                 "ct" => $ct,
                 "cn" => $cn);
    
    // Handle special cases
    
    // Handle standard controls
    if ($ct === 'boolean') {
        if (! ($value == '1' or $value == '0')) {
            pclog("f32 checkbox, invalid value : $value");
            return null;
        }

        $ret['value'] = f32Command($tn, $cn, $value);
        return $ret;
    } 
    elseif ($ct === 'select' || $ct === 'range') {
        $ret['value'] = f32Command($tn, $cn, $value);
        return $ret;
    }
    elseif ($ct === 'stateless') {
        f32Command($tn, $cn, $value);
        return null;
    }
}

function setScalerControl($tn, $ct, $cn, $value) {
    $ret = array( "tt" => "scaler",
                  "tn" => $tn,
                  "ct" => $ct,
                  "cn" => $cn,
                  "value" => $value);
    
    //pcLog("setScalerControl");

    if ($ct == 'writeonly') {
        if (ia200Command($tn, "W$cn$value")) {
            setScalerValue($tn, $cn, $value);
            return $ret;
        }

        return null;
    }
    elseif ($cn == 'test') {
        $cmd = 'WCBz';
        if ($value) {
            $cmd = 'WCBA';
        }
        if (ia200Command($tn,$cmd)) {
            setScalerValue($tn, 'test', $value);
            return $ret;
        }
        $ret['value'] = 0;
        return $ret;
    }

    //
    elseif ($ct == 'range' || $ct == 'select' || $ct == 'boolean') {
        if ($value >= 10000 || $value <= -1000) {
            pclog("setScalerControl: value out of range: $value");
            return null;
        }
        if ($value < 0) {
            $valpos = 0 - $value;
            $valstr = sprintf("-%03d",$valpos);
        }
        $valstr = sprintf("%04d", $value);
        $retval = ia200Command($tn, "W$cn$valstr");
        
        // ia200 will say ok even if value is out of range for it,
        // and it's ranges are poorly documented
        usleep(100000);
        $retval = ia200Command($tn, "R$cn");
        $ret['value'] = $retval;
        return $ret;
    }
}

function setScalerValue($scaler, $field, $value) {
    global $mysqli;
    $set_source = $mysqli->prepare(
        "insert into scaler_values (scaler, field, value) 
            values (?, ?, ?) 
            ON duplicate key update value= ?");

    if (! $set_source) 
        pclog("prepare set_source error: {$mysqli->error}");

    $set_source->bind_param('ssss', $scaler, $field, $value, $value);


    if(! $set_source->execute())
        pclog("execute set_source error: {$mysqli->error}");
}


function radioHTML($set_id, $value, $label) {
    $id = getID();

    echo("<input type='radio' name='$set_id' id='$id'
            class='source-opt' data-value='$value' />
                <label for='$id'>$label</label>");

}






    

