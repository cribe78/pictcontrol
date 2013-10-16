
var screenList = [ "1", "2", "3", "4", "5" ];
var pcontrols = {};
var log_idx = 0;
var cmd_idx = 0;
var id_idx = 1;
var sequences = {};


// Pict button
// - button types:
//    - bound to bool
//    - bound to multi-valued key
//

// ut = ui_type (slider, checkbox, radio) 
// ct = control_type (range, boolean, select)
// cn = control_name
// tt = target_type
// tn = target_name 
// 


$( document ).ready(function() {
    $("#tabsA").tabs();
    $("#tabsB").tabs({
        activate: function (event, ui) {
            var openIdx = $("#tabsB").tabs("option", "active");
            $("#tabsC").tabs("option", "active", openIdx);
            $("#tabsE").tabs("option", "active", openIdx);
            //console.log("proj tab " + openIdx + " opened");
        }
    });
    $("#tabsC").tabs({
        activate: function (event, ui) {
            var openIdx = $("#tabsC").tabs("option", "active");
            $("#tabsB").tabs("option", "active", openIdx);
            $("#tabsE").tabs("option", "active", openIdx);
            //console.log("scaler tab " + openIdx + " opened");
        }
    });

    $("#tabsD").tabs();

    $("#tabsE").tabs({
        activate: function (event, ui) {
            var openIdx = $("#tabsE").tabs("option", "active");
            $("#tabsB").tabs("option", "active", openIdx);
            $("#tabsC").tabs("option", "active", openIdx);
            //console.log("scaler tab " + openIdx + " opened");
        }
    });


    initPControls();
    initSeqEditor();
    getLogs();
    getCommands();
});


function commandSortable(tt, tn, cn, value) {
    var cnDisp = cn;
    if (commands[tt][cn]) {
        cnDisp = commands[tt][cn].name;
    }

    var valDisp = value;
    if (commands[tt][cn]) {
        if (commands[tt][cn].type == "select") {
            valDisp = commands[tt][cn].values[value];
        }
        else if (commands[tt][cn].type == "boolean") {
            valDisp = value == 1 ? "On" : "Off";
        }
    }
    
    var id = "cs" + id_idx;
    id_idx++;
    
    var cmdhtml = "<li id='" + id + "' class='ui-state-default' data-tt='" 
                    + tt + "' data-tn='" + tn
                    + "' data-cn='" + cn + "' data-value='" + value + "'>" 
                    + tt + ":" + tn
                    + " " + cnDisp + " " + valDisp
                    + "<button class='deletecmd'></button>";

    return cmdhtml;
}

function executeCommand(tt, tn, ct, cn, value) {
    $.ajax({
        url : "/commands.json",
        type : "POST",
        dataType : "json",
        data : {
            tt : tt,
            tn : tn,
            ct : ct,
            cn : cn,
            value : value
        }
    });
}

function getCommands() {
    $.ajax({
        url : "/commands.json",
        type : "GET",
        dataType : "json",
        data : {
            cmd_idx : cmd_idx  },
        success : function (json) {
            if (typeof json.pcontrols != undefined)
                loadJsonControlValues(json);

            cmd_idx = json.cmd_idx;
        },
        complete : getCommandsWait
    });
}

function getCommandsWait(reqObj, status) {
    console.log("get commands wait");
    setTimeout(getCommands, 100);
}


function getLogs() {
    $.ajax({
        url : "/log.json",
        type : "GET",
        dataType : "json",
        data : {
            idx : log_idx
        },
        success : function (json) {
            if (typeof json.log != undefined)
                loadLogMessages(json.log);

            console.log("logs got");
        }
       // complete: getLogsWait
    });
}


function getLogsWait(reqObj, status) {
    setTimeout(getLogs, 100);
}


function getPControl(tt, tn, cn) {
    if (tt === undefined || 
        tn === undefined ||
        cn === undefined) {
            return null;
    }

    if (pcontrols[tt] === undefined) {
        pcontrols[tt] = {};
    }
    if (pcontrols[tt][tn] === undefined) {
        pcontrols[tt][tn] = {};
    }
    if (pcontrols[tt][tn][cn] === undefined) {
        var newpc = new PControl(tt, tn, cn);
        pcontrols[tt][tn][cn] = newpc;
    }

    return pcontrols[tt][tn][cn];
}

// Get the PControl object associated with a jQuery object
function jqGetPControl(jqo) {
    return getPControl(jqo.data("tt"), jqo.data("tn"), jqo.data("cn"));
}

// Get the Pcontrol object associated with a JSON object
function jsonGetPControl(jo) {
    return getPControl(jo.tt, jo.tn, jo.cn);
}


function initDeleteCmd() {
    $(".deletecmd").button({
        icons: { primary : 'ui-icon-trash' }
    })
    .click( function() {
        $(this).parent().remove();
        updateSeqList();
    });

}

function initSeqEditor() {
    $.ajax({
        url : "/sequences.json",
        dataType : "json",
        success : function (json) {
            if (typeof json.sequences != undefined)
                loadSequences(json.sequences);

            console.log("sequences got");
        }
    });

    var nctargets = "";
    for (var pc in commands) {
        console.log("command target: " + pc);
        nctargets += selectOption(pc, pc);
    }

    $("#newcommandtt").append(nctargets);

    $("#newcommandtt").change( function() {
        var cmdopts = selectOption("Command", "Command");
        var tt = this.value;

        for (var cn in commands[tt]) {
            var name = cn;
            if (commands[tt][cn] != undefined) {
                name = commands[tt][cn].name;
            }
            if (! commands[tt][cn].readonly) {
                cmdopts += selectOption(cn, name);
            }
        }

        $("#newcommandcn").html(cmdopts);

    });

    $("#newseqadddiv").hide();
    $("#newcommandrangevalue").hide();

    $("#newseqadd").button()
    .click( function() {
        var name = $("#newseq").val();
        $.ajax({
            url : "/sequences.json",
            type : "POST",
            dataType : "json",
            data : {
                sequence : { name : name }
            },
            success : function(json) {
                if (json.status == 1) {
                    var newopt = selectOption(json.sequence.seq_id, 
                                              json.sequence.name);
                    $("#newseqadddiv").hide();
                    $("#seqs").append(newopt)
                    .val(json.sequence.seq_id);
                    $("#seqselectdiv").show();

                    console.log("sequence added");
                }
            }
        });
        
    });

    $("#newseqcancel").button()
    .click( function() {
        $("#newseqadddiv").hide();
        $("#seqselectdiv").show();
        $("#newseqadd").val("Name");
    });
        
    $("#seqexecute").button()
    .click( function() {
        var seq_id = $("#seqs").val();
        $.ajax({
            url : "/exec-sequence.json",
            type : "POST",
            dataType : "json",
            data : {
                seq_id : seq_id
            },
            success : function(json) {
                if (json.status == 1) {
                    // no need to do nothing
                }
            }
        });
    });



    $("#seqs").change( function() {
        if ( this.value == "new" ) {
            $("#seqselectdiv").hide();
            $("#newseqadddiv").show();
            $("#seqsortlist").empty();
        }
        else {
            loadSeq(this.value);
        }
    });

    
    $("#newcommandcn").change( function() {
        var cn = this.value;
        var tt = $("#newcommandtt").val();
        var selecthtml = "";

        var ct = commands[tt][cn].type;

        if (ct == "boolean") {
            selecthtml += selectOption(0, "Off");
            selecthtml += selectOption(1, "On");
            $("#newcommandvalue").show();
            $("#newcommandrangevalue").hide();
        }
        else if (ct == "select") {
            for (var val in commands[tt][cn].values) {
                selecthtml += selectOption(val, commands[tt][cn].values[val]);
            }
            $("#newcommandvalue").show();
            $("#newcommandrangevalue").hide();
        }
        else if (ct == "range") {
            var min = commands[tt][cn].min;
            var max = commands[tt][cn].max;
            $("#newcommandrangevalue").spinner({
                spin: function(event, ui ) {
                    if ( ui.value > max ) {
                        $(this).spinner("value", max);
                        return false;
                    }
                    else if ( ui.value < min) {
                        $(this).spinner("value", min);
                        return false;
                    }
                }
            });
            
            $("#newcommandvalue").hide();
            $("#newcommandrangevalue").show();
        }

        $("#newcommandvalue").html(selecthtml);
    });



    $("#newcommandadd").click(function() {
        var tt = $("#newcommandtt").val();
        var tn = $("#newcommandtn").val();
        var cn = $("#newcommandcn").val();
        var val = $("#newcommandvalue").val();
        
        if (cn == "Command" ||
            tt == "Target") {
            return;
        }

        if (commands[tt][cn].type == "range") {
            val = $("#newcommandrangevalue").val();
        }

        var htmlli = commandSortable(tt, tn, cn, val);

        $("#seqsortlist").append(htmlli);
        updateSeqList();
        initDeleteCmd();
    });

    $("#seqsortlist").sortable( {
        update : updateSeqList
    });
}



function initPControls() {
    // Go through all of the input elements in the document and build 
    // the PControl objects
    $(".pcontrol").each(function() {
        var jqo = $(this);
        var pc = jqGetPControl(jqo);
        pc.addJqObject(jqo);
        var ut = jqo.data("ut");

        // Some controls 

        if (ut == "checkbox") {
            jqo.button();
        }
        else if (ut == "slider") {
            jqo.slider( {
                min: commands[pc.tt][pc.cn].min,
                max: commands[pc.tt][pc.cn].max,
                change: function(event, ui) {
                    var jqo = $(this);
                    var pc = jqGetPControl(jqo);
                    var val = ui.value;
                    console.log("slider change, value: " + val);
                    pc.updateValue(val);
                }
            });
        }   
        else if (ut == "radio") {
            jqo.buttonset();
        }
        else if (ut == "button") {
            jqo.button();
        }
        else if (ut == "spinner") {
            var min = commands[pc.tt][pc.cn].min;
            var max = commands[pc.tt][pc.cn].max;

            jqo.spinner({
                spin : function (event, ui) {
                    if (ui.value > max) {
                        $(this).spinner("value", max);
                        return false;
                    }
                    else if (ui.value < min) {
                        $(this).spinner("value", min);
                        return false;
                    }
                },
                change : function (event, ui) {
                    var jqo = $(this);
                    var pc = jqGetPControl(jqo);
                    var val = pctlVal(jqo);
                    console.log("spinner change called val=" + val);
                    pc.updateValue(val);
                }
            });
        }
    })
    .on("change", function (event, ui) {
        var jqo = $(this);
        var pc = jqGetPControl(jqo);
        var val = pctlVal(jqo);
        console.log("on change called val=" + val);
        pc.updateValue(val);
    })
    .click( function (event) {
        var jqo = $(this);
        var pc = jqGetPControl(jqo);
        if (pc.ct == 'stateless') { 
            executeCommand(pc.tt, pc.tn, pc.ct, pc,cn, jqo.data("value"));
        }
    });

    $(".targetrefresh").button().click( function() {
        refreshTarget($(this).data("tt"), $(this).data("tn"));
    });

    $(".plabel").each( function() {
        var tt = $(this).data("tt");
        var cn = $(this).data("cn");

        $(this).text(commands[tt][cn].name);
    });

}

function loadJsonControlValues(json) {
    var pctls = json.pcontrols;
    for (var idx in pctls) {
        if (pctls[idx] !== null) {
            var pc = jsonGetPControl(pctls[idx]);
            pc.setValue(pctls[idx].value);

        }
    }
}

function loadLogMessages(logs) {
    var logStr = "";
    for (var i = logs.length - 1; i >= 0; i--) {
        logStr += logs[i].log_str + "<br />";
    }

    if (logs.length > 0) 
        log_idx = logs[logs.length - 1].log_id;

    $("#tabsA-6").prepend(logStr);
}


function loadSeq(seqID) { 
    var listhtml = "";

    console.log("load sequence " + seqID); 
    for (var idx in sequences[seqID].commands) {
        var cmd = sequences[seqID].commands[idx];

        listhtml += commandSortable(cmd.tt, cmd.tn, cmd.cn, cmd.value);
    }

    $("#seqsortlist").html(listhtml);

    initDeleteCmd();
}

function loadSequences(json) {
    sequences = json;
    
    $(".pseq").each( function() {
        var seqID = $(this).data("id");

        $(this).button()
        .click( function (event) {
            $.ajax({
                url : "/exec-sequence.json",
                type : "POST",
                dataType : "json",
                data : {
                    seq_id : seqID
                },
                success : function(json) {
                    if (json.status == 1) {
                        // no need to do nothing
                    }
                }
            });
        })
        .children("span").text(sequences[seqID].name);
    });

    var opthtml = "";

    for (var seq_id in sequences) {
        opthtml += selectOption(seq_id, sequences[seq_id].name);
    }
    
    opthtml += selectOption("new", "New...");

    $("#seqs").html(opthtml);

    loadSeq($("#seqs").val());
}

function pctlVal(jqo) {
    var type = jqo.data("ut");

    if (type == "slider") {
        return jqo.slider("value");
    }
    else if (type == "checkbox") {
        var checked = jqo.prop("checked");
        if (checked) {
            return 1;
        }
        else {
            return 0;
        }
    }
    else if (type == "radio") {
        var inputs = jqo.find("input");
        for (var idx in inputs) {
            if ($(inputs[idx]).prop("checked")) {
                return $(inputs[idx]).data("value");
            }
        }
    }
    else if (type == "spinner") {
        return jqo.spinner("value");
    }
}

function PControl(tt, tn, cn) {
    if (tt === undefined ||
        tn === undefined ||
        cn === undefined) {
        console.log("pcontrol init: undefined value");
        return null;
    }

    if (typeof commands[tt][cn] == "undefined") {
        console.log("undefined command " + tt + ":" + cn);
        return null;
    }


    this.tt = tt;
    this.tn = tn;
    this.ct = commands[tt][cn].type;
    this.cn = cn;
    this.value = null;
    this.jqObjects = [];
    console.log("pcontrol init: tt=" + tt + " ,tn=" + tn + ", ct=" 
                + this.ct + ", cn=" + cn);
}


PControl.prototype.addJqObject = function(jqObj) {
    //console.log("addJqObj: " + this.toString());
    this.jqObjects.push(jqObj);
    if (this.value !== null) {
        setJQCtlValue(jqObj, this.value);
    }
}

PControl.prototype.setValue = function(val) {
    if (val === null) 
        return;

    //console.log("PControl.setValue " + val); 
    this.value = val;

    for (var ctlIdx in this.jqObjects) {
        //console.log("PControl.setValue, object " + ctlIdx);
        var ctl = this.jqObjects[ctlIdx];
        setJQCtlValue(ctl, val);       
    }

    // Keep polling the server if this is a projector that is warming up 
    if (this.tt == "proj" && this.cn == "POST"
        && (val == 2 || val == 4)) {
        executeCommand(this.tt, this.tn, "query", "POST", "?");
        executeCommand(this.tt, this.tn, "query", "LST1", "?");
        executeCommand(this.tt, this.tn, "query", "LST2", "?");
    }
}

function refreshTarget(tt, tn) {
   for (var cmd in commands[tt]) { 
        var co = commands[tt][cmd];

        if (! co.writeonly) {
            $.ajax( {
                url : "/commands.json",
                type : "POST",
                dataType : "json",
                data : {
                    ct : "query",
                    tt : tt,
                    tn : tn,
                    cn : cmd,
                    value : "?"
                }
            });      
        }
   }
}


function selectOption(value, label) {
    var opthtml = "<option value='" + value + "'>" + label + "</option>";
    return opthtml;
}

function setJQCtlValue(ctl, val) {
    var ut = ctl.data("ut");

    if (ut === "checkbox") {
        var checked = (val == 1);
        if (checked != ctl.prop("checked")) {
            ctl.prop("checked", checked).button("refresh");
        }
    }
    else if (ut === "slider") {
        if (ctl.slider("value") != val &&
            ctl.slider("option", "max") >= val &&
            ctl.slider("option", "min") <= val) {
            ctl.slider("value", val);
        }
    }
    else if ( ut == "spinner") {
        ctl.spinner("value", val);
    }
    else if ( ut === "radio") {
        // Select the correct option
        ctl.find("input").each( function() {
            if ($(this).data("value") == val) {
                if (! $(this).prop("checked")) {
                    $(this).prop("checked", true).button("refresh");
                }
            }
            else {
                if ($(this).prop("checked")) {
                    $(this).prop("checked", false).button("refresh");
                }
            }   
        });
    }
    else if ( ut === "display") {
        var tt = ctl.data("tt");
        var cn = ctl.data("cn");
        var valDisp = val;

        if ( typeof commands[tt][cn] == "undefined") {
            console.log("warning: undefined command: " + tt + ":" + cn);
        }
        else if ( commands[tt][cn].type == "select") {
            valDisp = commands[tt][cn].values[val];
        }

        ctl.text(valDisp);
    }
}
    


PControl.prototype.toString = function() {
    var str = "tt=" + this.tt + ", tn=" + this.tn + ", ct=" + this.ct
                + ", cn=" + this.cn;
    return str;
}

PControl.prototype.toArray = function() {
    var array = { tt: this.tt,
                  tn: this.tn,
                  ct: this.ct,
                  cn: this.cn };
    return array;
}



PControl.prototype.updateValue = function(val) {
    if (val != this.value) {
        var pctl = this;
        executeCommand(this.tt, this.tn, this.ct, this.cn, val);
    }
    else {
        console.log("updateValue: no change, " + this.toString());
    }
}


function updateSeqList() {
    var cmdList = $("#seqsortlist").sortable("toArray");
    var name = $("#seqs option:selected").text();

    console.log("list sorted");
    var sequence = { name : name,
                     seq_id : $("#seqs").val() };
    sequence.commands = { };

    for (var idx = 0; idx < cmdList.length; idx++) {
        var item = $("#" + cmdList[idx]);
        sequence.commands[idx] = {
            tt : item.data("tt"),
            tn : item.data("tn"),
            cn : item.data("cn"),
            value : item.data("value")
        };
    }


    $.ajax( {
        url : "/sequences.json",
        type : "POST",
        dataType : "json",
        data : {
            sequence : sequence
        },
        success : function(json) {
            if (json.status == 1) {
                sequences[json.sequence.seq_id] = json.sequence;
                console.log("sequence updated");
            }
        }
    });
}



