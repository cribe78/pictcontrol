<?php

$tb_id = getID();
$pwr_id = getID();
$shut_id = getID();
$source_id = getID();

?>


<div id='<?=$tb_id?>' class="screen<?=$p?> picttb ui-widget-header ui-corner-all">
    <span><img src="images/<?=$screen_icons[$p]?>" class="tbicon" /></span>
    <input type='checkbox' id='<?=$pwr_id?>' class='screen<?=$p?> pcontrol'
    data-ut='checkbox' data-ct='boolean' data-cn='POWR' data-tt='proj' data-tn='<?=$p?>' /> 
    <label for='<?=$pwr_id?>'>Power</label>

    <span id="<?=$source_id?>" class="pcontrol"  
        data-ct='writeonly' data-ut='radio' data-cn='BA' data-tt='scaler' data-tn='<?=$p?>'>
<?
radioHTML($source_id, 'A', "Component");
radioHTML($source_id, 'B', "VGA");
radioHTML($source_id, 'C', "DVI");
radioHTML($source_id, 'G', "HDMI");
?>
    </span>
</div>
