var clientInformation = {
    username: new Date().toTimeString()
};

/**
 * @type WebSocket
 */
var conn = new WebSocket('ws://127.0.0.1:8080/chat');

conn.onopen = function() {
    console.info("Connection established succesfully");
};

conn.onmessage = function(e) {
    var data = JSON.parse(e.data);
    Chat.appendMessage(data.username, data.message);

    console.log(data);
};

conn.onerror = function(e){
    alert("Error: something went wrong with the socket.");
    console.error(e);
};

document.getElementById("form-submit").addEventListener("click",function(){
    var msg = document.getElementById("form-message").value;

    if(!msg){
        alert("Please send something on the chat");
    }

    Chat.sendMessage(msg);
    document.getElementById("form-message").value = "";
}, false);

var Chat = {
    appendMessage: function(username,message){
        var from;

        if(username === clientInformation.username){
            from = "me";
        }else{
            from = clientInformation.username;
        }

        var ul = document.getElementById("chat-list");
        var li = document.createElement("li");
        li.appendChild(document.createTextNode(from + " : "+ message));
        ul.appendChild(li);
    },
    sendMessage: function(text){
        clientInformation.message = text;
        conn.send(JSON.stringify(clientInformation));
        this.appendMessage(clientInformation.username, clientInformation.message);
    }
};
