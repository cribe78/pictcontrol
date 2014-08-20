<?php

$out_labels[1] = array("", "PICT1", "PICT2", "PICT3", "PICT4", "PICT5", "MR", "7", "8");
$in_labels[1] = array("", "SAT1", "SAT2", "SAT3", "BD", "5", "6", "7", "8");

$out_labels[2] = array("", "Left", "Back", "Right", "Podium L", "Podium R", "GA Desk", "Program", "Podium F");
$in_labels[2] = array("Mute", "V1", "V2", "V3", "V4", "5", "Laptop", "Aux", "8");

?>
<div class="matrixcontrol">
    <div>Video</div>
    <div>
<?
for ($i = 1; $i <= 8; $i++) {
    $input_id = getID();
?>
    <div class="ui-widget-toolbar ui-corner-all">
    <div class='swtoolbarlabel'><?=$out_labels[$tn][$i]?></div>
    <span id="<?=$input_id?>" class="pcontrol"  
          data-ut='radio' data-cn='Out<?=$i?>Vid' data-tt='switcher-dxp' data-tn='<?=$tn?>'>
<?
    for ($j = 0; $j <= 8; $j++) {
        radioHTML($input_id, $j, $in_labels[$tn][$j]);
    }
?>
    </span>
    </div>
<? 
}
?>
</div>
</div>
<div class="matrixcontrol">
<div>Audio</div>
<div>
<?

for ($i = 1; $i <= 8; $i++) {
    $input_id = getID();
?>
    <div class="ui-widget-toolbar ui-corner-all">
    <span id="<?=$input_id?>" class="pcontrol"  
          data-ut='radio' data-cn='Out<?=$i?>Aud' data-tt='switcher-dxp' data-tn='<?=$tn?>'>
<?
    for ($j = 1; $j <= 8; $j++) {
        radioHTML($input_id, $j, $in_labels[$tn][$j]);
    }
?>
    </span>
    <div class='swtoolbarlabel'><?=$out_labels[$tn][$i]?></div>
    </div>
<? 
}
?>
</div>
</div>


