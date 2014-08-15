<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 7/17/14
 * Time: 11:21 AM
 *
 * Configuration file for the controlVconsole app.  This script outputs JSON data that controlV uses to create
 * it's control objects
 */

include("sub/gentner-ports.php");

$v1 = array( "name" => "V1", "input" => "1");
$v2 = array( "name" => "V2", "input" => "2");
$v3 = array( "name" => "V3", "input" => "3");
$v4 = array( "name" => "V4", "input" => "4");
$lt = array( "name" => "LT", "input" => "6");
$aux = array( "name" => "AUX", "input" => "7");


$switcher_sources = array($v1, $v2, $v3, $v4, $lt, $aux);

$target = array("tt" => "switcher-dxp", "tn" => 2);
$audio_target = array("tt" => "xap800", "tn" => 1);

$orc_config = array(
    "audio-target" => $audio_target,
    "audio-xpoints" => array(),
    "switcher-target" => $target,
    "switcher-dests" => array(
        array(
            "name" => "Left",
            "output" => 1,
            "type" => "Vid",
            "ties" => array("Program Video", "Program Audio 1", "Program Audio 2", "Program Audio3"),
            "power-control" => array(
                "tt" => "proj-benq",
                "tn" => 1,
                "cn" => "POW"
            ),
            "sources" => $switcher_sources,
            "col" => 0,
            "row" => 4
        ),
        array(
            "name" => "Back",
            "output" => 2,
            "type" => "Vid",
            "sources" => $switcher_sources,
            "col" => 2,
            "row" => 0
        ),
        array(
            "name" => "Right",
            "output" => 3,
            "type" => "Vid",
            "power-control" => array(
                "tt" => "proj-benq",
                "tn" => 2,
                "cn" => "POW"
            ),
            "sources" => $switcher_sources,
            "col" => 4,
            "row" => 4
        ),
//        array(
//            "name" => "Podium R",
//            "output" => 4,
//            "type" => "Vid",
//            "sources" => $switcher_sources,
//            "col" => 3,
//            "row" => 3
//        ),
//        array(
//            "name" => "GA Desk",
//            "output" => 6,
//            "type" => "Vid",
//            "sources" => $switcher_sources,
//            "col" => 4,
//            "row" => 2
//        ),
        array(
            "name" => "Program Video",
            "output" => 7,
            "type" => "Vid",
            "sources" => array($v2, $v3, $v4, $aux),
            "col" => 5,
            "row" => 4
        ),
        array(
            "name" => "Program Audio 1",
            "output" => 7,
            "type" => "Aud",
            "sources" => $switcher_sources,
            "hidden" => true
        ),
        array(
            "name" => "Program Audio 2",
            "output" => 1,
            "type" => "Aud",
            "sources" => $switcher_sources,
            "hidden" => true
        ),
        array(
            "name" => "Program Audio 3",
            "output" => 2,
            "type" => "Aud",
            "sources" => $switcher_sources,
            "hidden" => true
        ),
    ),

    "switcher-cols" => array(20, 320, 480, 640, 1000, 1340, 1800),
    "switcher-rows" => array(0, 200, 250, 400, 500)
);


for ($i = 1; $i <= 12; $i++) {
    $on_val = "1";
    if ($i <= 8) $on_val = "4";  // "gated"
    for($o = 1; $o <= 12; $o++) {
        if (is_array($gentner_ports["xpoints"][$i]) &&
                isset($gentner_ports["xpoints"][$i][$o]) &&
                $gentner_ports["xpoints"][$i][$o] == true) {
            $orc_config["audio-xpoints"][] = array(
                "input-name" => $gentner_ports["in"][$i],
                "input" => $i,
                "output" => $o,
                "output-name" => $gentner_ports["out"][$o],
                "cn-lvl" => "MTRXLVL $i I $o O",
                "cn-mute" => "MTRX $i I $o O",
                "range-min" => -60,
                "range-max" => 0,
                "on-val" => $on_val
            );
        }


    }
}


echo(json_encode($orc_config));