var username;
$(document).ready(function(){
    username = $('#username').html();
    // alert(username);
    pullData();
    $(document).keyup(function(e){
        if(e.keyCode == 13){ // If user press enter
            // alert("user press enter");
            sendMessage();
        }else{
            // alert("user is typing");
            isTyping(); // If user is just typing 
        }
    });
});

function pullData(){
    retrieveChatMessages();
    retrieveTypingStatus();
    setTimeout(pullData, 2000);
}

function retrieveChatMessages(){
    $.post('/chat/retrieveChatMessages', {username: username}, 
    function(data){
        if(data.length > 0){
            $('.chat-window').append('<br><div>'+data+'</div><br>');
        }
    })
}

function retrieveTypingStatus(){
    $.post('/chat/retrieveTypingStatus', {username: username}, 
        function(username){
            if(username.length > 0){
                $("#typingStatus").html(username+' is typing');
            }else{
                $("#typingStatus").html('');
            }
    })
}

function sendMessage(){
    var text = $('#text').val();
    if(text.length > 0){
        $.post('/chat/sendMessage', {text: text, username:username}, function(){
            $('.chat-window').append('<br><div style="text-align: right"><strong>'+username+': </strong>'+text+'</div><br>');
            $("#text").val("");
            notTyping();
        });
    }
}

function isTyping(){
    // alert(username);
    username = $('#username').html();
    // alert(username);
    $.post('/chat/isTyping', {username: username});   
}

function notTyping(){
    $.post('/chat/notTyping', {username: username});   
}