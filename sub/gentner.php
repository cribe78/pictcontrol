<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 8/12/14
 * Time: 10:35 AM
 */


gentnerCrosspoint(1, 1, "Mic (1/2) -&gt; Online (1/2)"); // 1, 25
gentnerCrosspoint(1, 5, "Mic (1/2) -&gt; Full Mix (5/6)"); // 5, 19
gentnerCrosspoint(9, 3, "V4 (9/10) -&gt; House (3/4)"); // 4, 20
gentnerCrosspoint(9, 5, "V4 (9/10) -&gt; Full Mix (5/6)"); // 6, 21
gentnerCrosspoint(11, 1, "PGM (11/12) -&gt; Online (1/2)");  // 3, 22
gentnerCrosspoint(11, 3, "PGM (11/12) -&gt; House (3/4)"); //2, 23,
gentnerCrosspoint(11, 5, "PGM (11/12) -&gt; Full Mix (5/6)"); // 7, 24



function gentnerCrosspoint($input, $output, $label) {
    $mute_id = getID();
    $mtx_id = "$input I $output O";
?>
    <div class='ui-widget-header ui-corner-all'>
    <div class='ptoolbarlabel'><?=$label?></div>
    <div class="pslider pcontrol"
        data-tt='xap800' data-tn='1'  data-ut='slider' data-ct='range' data-cn='MTRXLVL <?=$mtx_id?>'
        data-min='-60' data-max='0'> </div>
    <span class="pcontrol"  data-tt='xap800' data-tn='1'
        data-ut='display' data-ct='range' data-cn='MTRXLVL <?=$mtx_id?>'></span>
        <input type='checkbox' id='<?=$mute_id?>' class='pcontrol'
               data-ut='checkbox' data-ct='boolean' data-cn='MTRX <?=$mtx_id?>' data-tt='xap800' data-tn='1' />
        <label for='<?=$mute_id?>'>Enable</label>
</div>

<?
}