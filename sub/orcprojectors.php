<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 7/8/14
 * Time: 9:57 AM
 */


$names = array(1 => "Left", 2 => "Rear", 3 => "Right");

foreach ($names as $idx => $name) {
    $pwr_id = getID();
?>
    <div class="picttb ui-widget-header ui-corner-all">
        <span><?=$name?></span>
        <input type='checkbox' id='<?=$pwr_id?>' class='pcontrol'
        data-ut='checkbox' data-ct='boolean' data-cn='POW' data-tt='proj-benq' data-tn='<?=$idx?>' />
        <label for='<?=$pwr_id?>'>Power</label>
    </div>
<?
}
