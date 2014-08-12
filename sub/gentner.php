<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 8/12/14
 * Time: 10:35 AM
 */


gentnerCrosspoint(11, 3, "PGM -&gt; House L");
gentnerCrosspoint(12, 4, "PGM -&gt; House R");
gentnerCrosspoint(9, 3, "V4 -&gt; House L");
gentnerCrosspoint(10, 4, "V4 -&gt; House R");
gentnerCrosspoint(11, 1, "PGM -&gt; Online L");
gentnerCrosspoint(12, 2," PGM -&gt; Online R");
gentnerCrosspoint(1, 1, "Mic -&gt; Online L");
gentnerCrosspoint(1, 2, "Mic -&gt; Online R");


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