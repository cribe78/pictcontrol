<?php
// Sequence editor


?>

<div id="seqeditheader">
    <div id=seqselectdiv>
        <select id=seqs>

        </select>
        <button id=seqexecute>Execute</button>
        <button id=seqrename>Rename</button>
    </div>
    <div id=newseqadddiv>
        <input id=newseq value="Name"/>
        <button id=newseqadd>Add</button>
        <button id=newseqcancel>Cancel</button>
    </div>
    <div id=seqrenamediv>
        <input id=seqrenamename value="Name"/>
        <button id=seqrenameupdate>Update</button>
        <button id=seqrenamecancel>Cancel</button>
    </div>
</div>
<div id="seqeditbody">
    <ul id="seqsortlist">

    </ul>
</div>
<div id="newcommand">
    <select id=newcommandtt>
        <option value="Target">Target</option>
    </select>
    <select id=newcommandtn>
        <option value=1>1</option>
        <option value=2>2</option>
        <option value=3>3</option>
        <option value=4>4</option>
        <option value=5>5</option>
    </select>
    <select id=newcommandcn>
        <option>Command</option>
    </select>
    <select id="newcommandvalue">
        <option>Value</option>
    </select>
    <input id="newcommandrangevalue" />
    <button id=newcommandadd>Add</button>
</div>

