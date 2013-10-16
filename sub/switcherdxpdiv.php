<?php

$out_labels = array("", "PICT1", "PICT2", "PICT3", "PICT4", "PICT5", "MR", "7", "8");
$in_labels = array("", "SAT1", "SAT2", "SAT3", "BD", "5", "6", "7", "8");
?>
<div class="matrixcontrol">
    <div>Video</div>
    <div>
<?
for ($i = 1; $i <= 8; $i++) {
    $input_id = getID();
?>
    <div class="ui-widget-toolbar ui-corner-all">
    <div class='swtoolbarlabel'><?=$out_labels[$i]?></div>
    <span id="<?=$input_id?>" class="pcontrol"  
          data-ut='radio' data-cn='Out<?=$i?>Vid' data-tt='switcher-dxp' data-tn='1'>
<?
    for ($j = 1; $j <= 8; $j++) {
        radioHTML($input_id, $j, $in_labels[$j]);
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
          data-ut='radio' data-cn='Out<?=$i?>Aud' data-tt='switcher-dxp' data-tn='1'>
<?
    for ($j = 1; $j <= 8; $j++) {
        radioHTML($input_id, $j, $in_labels[$j]);
    }
?>
    </span>
    <div class='swtoolbarlabel'><?=$out_labels[$i]?></div>
    </div>
<? 
}
?>
</div>
</div>


