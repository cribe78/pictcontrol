<?php

$js_includes = array("controldefs.js", "controlpanel.js");
$css_includes = array("css/controlpanel.css");
require("sub/head.php");


?>

<body>
<div id=loadingImg>
    <span id=loadingText>Initializing...</span>
</div>
<div id="tabsA">
    <ul id="tablistA">
        <li><a href="#tabsA-1">Proj/Amp</a></li>
        <li><a href="#tabsA-2">Switcher</a></li>
        <li><a href="#tabsA-3">Gentner</a></li>
    </ul>

    <div id="tabsA-1">
        <?
        include("sub/orcprojectors.php");
        ?>
        <div class='ui-widget-header ui-corner-all'>
            <div class='ptoolbarlabel'>Master Volume</div>
            <div class="pslider pcontrol"
                 data-tt='audio-onkyo-eth' data-tn='1'  data-ut='slider' data-ct='range' data-cn='MVL'
                 data-min='0' data-max='80'> </div>
                <span class="pcontrol"  data-tt='audio-onkyo-eth' data-tn='1'
                    data-ut='display' data-ct='range' data-cn='MVL'></span>
        </div>

        <?
        radioBar("audio-onkyo-eth", 1, 'SLI');
        ?>
    </div>
    <div id="tabsA-2">
        <?
        $tn = 2;
        include("sub/switcherdxpdiv.php");
        ?>
    </div>
    <div id="tabsA-3">
        <?
        include("sub/gentner.php");
        ?>
    </div>
</div>
</body>


