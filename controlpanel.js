
var projectors = []; 
var scalers = [];
var screenList = [ "1", "2", "3", "4", "5" ];
var pcontrols = {};
var log_idx = 0;
var cmd_idx = 0;

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
            //console.log("proj tab " + openIdx + " opened");
        }
    });
    $("#tabsC").tabs({
        activate: function (event, ui) {
            var openIdx = $("#tabsC").tabs("option", "active");
            $("#tabsB").tabs("option", "active", openIdx);
            //console.log("scaler tab " + openIdx + " opened");
        }
    });

    $("#tabsD").tabs();

    initPControls();
    getLogs();
    getCommands();
});


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


function getPControl(tt, tn, ct, cn) {
    if (tt === undefined || 
        tn === undefined ||
        ct === undefined ||
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
        var newpc = new PControl(tt, tn, ct, cn);
        pcontrols[tt][tn][cn] = newpc;
    }

    return pcontrols[tt][tn][cn];
}

// Get the PControl object associated with a jQuery object
function jqGetPControl(jqo) {
    return getPControl(jqo.data("tt"), jqo.data("tn"), jqo.data("ct"), jqo.data("cn"));
}

// Get the Pcontrol object associated with a JSON object
function jsonGetPControl(jo) {
    return getPControl(jo.tt, jo.tn, jo.ct, jo.cn);
}



function initPControls() {
    // Go through all of the input elements in the document and build 
    // the PControl objects
    $(".pcontrol").each(function() {
        var jqo = $(this);
        var pc = jqGetPControl(jqo);
        pc.addJqObject(jqo);

        // Some controls 

        if (jqo.data("ut") == "checkbox") {
            jqo.button();
        }
        else if (jqo.data("ut") == "slider") {
            jqo.slider( {
                min: jqo.data("min"),
                max: jqo.data("max"),
                change: function(event, ui) {
                    var jqo = $(this);
                    var pc = jqGetPControl(jqo);
                    var val = ui.value;
                    console.log("slider change, value: " + val);
                    pc.updateValue(val);
                }
            });
        }   
        else if (jqo.data("ut") == "radio") {
            jqo.buttonset();
        }
        else if (jqo.data("ut") == "button") {
            jqo.button();
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
            $.ajax({
                url : "/commands.json",
                type : "POST",
                dataType : "json",
                data : {
                    tt : pc.tt,
                    tn : pc.tn,
                    ct : pc.ct,
                    cn : pc.cn,
                    value : jqo.data("value")
                },
                success : function(json) {
                    if (json.status == 1) {
                        loadJsonControlValues(json);
                    }   
                }
            });
        }
    });

    $(".pseq").each( function() {
        var name = $(this).data("name");
        $(this).button()
        .click( function (event) {
            $.ajax({
                url : "/exec-sequence.json",
                type : "POST",
                dataType : "json",
                data : {
                    seq : name
                },
                success : function(json) {
                    if (json.status == 1) {
                        loadJsonControlValues(json);
                    }
                }
            });
        });
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
}

function PControl(tt, tn, ct, cn) {
    if (tt === undefined ||
        tn === undefined ||
        ct === undefined ||
        cn === undefined) {
        console.log("pcontrol init: undefined value");
        return null;
    }

    this.tt = tt;
    this.tn = tn;
    this.ct = ct;
    this.cn = cn;
    this.value = null;
    this.jqObjects = [];
    console.log("pcontrol init: tt=" + tt + " ,tn=" + tn + ", ct=" 
                + ct + ", cn=" + cn);
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

    console.log("PControl.setValue " + val); 
    this.value = val;

    for (var ctlIdx in this.jqObjects) {
        console.log("PControl.setValue, object " + ctlIdx);
        var ctl = this.jqObjects[ctlIdx];
        setJQCtlValue(ctl, val);       
    }
}

function setJQCtlValue(ctl, val) {
    if (ctl.data("ut") === "checkbox") {
        var checked = (val == 1);
        if (checked != ctl.prop("checked")) {
            ctl.prop("checked", checked).button("refresh");
        }
    }
    else if (ctl.data("ut") === "slider") {
        if (ctl.slider("value") != val &&
            ctl.slider("option", "max") >= val &&
            ctl.slider("option", "min") <= val) {
            ctl.slider("value", val);
        }
    }
    else if (ctl.data("ut") === "radio") {
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
    else if (ctl.data("ut") === "display") {
        ctl.text(val);
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
        $.ajax( {
            url : "/commands.json",
            type : "POST",
            dataType : "json",
            data : {
                ct : this.ct,
                tt : this.tt,
                tn : this.tn,
                cn : this.cn,
                value : val
            },
            success : function(json) {
                if (json.status == 1) {
                    loadJsonControlValues(json);
                }   
            }
        });      
    }
    else {
        console.log("updateValue: no change, " + this.toString());
    }
}





