/*
 *  Send command (ON/OFF) to device-controller
 *  changing relay state.
 * */
function sendCommand(ip, relay, cmd){
    var bin_cmd = (cmd == true) ? 1 : 0;
    var url = "http://" + ip  + "/relay/" + relay + "/" + bin_cmd

    $.get(url, function(response){

    }).fail(function(){
        alert("Error sending command to server");
    });
}