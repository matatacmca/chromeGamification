//define server
var server = "https://hostName";//you will need to updatethis
var pageURL = document.URL;//retrieves the page URL
logPage(pageURL,server);//logs browsing to server
window.onclick = function(){logClick(server)};//logs clicking activity to server
var keystrokes = 0;//set keystrokes to 0, log every 10 times
window.onkeyup = function()//increment keystrokes
{
    keystrokes++;//increment keystrokes
    if(keystrokes >= 10)//when keystokes reaches 10, log to server
    {
        logTyping(server);//log typing to server
        keystrokes = 0;//reset keystrokes to 0
    }
};
window.onscroll = function(){logScrolling(server);};//log scrolling to server
function logPage(pageURL,server)
{
    $.ajax(
        {
            type: "post",
            url: server+"/log.php",
            data: "request=logPage&URL=" + pageURL,
            success: function(msg)
            {
                if(msg == "ERROR: noUser")//error handling returned from log.php
                {
                    window.alert('ERROR: chromeGamification not signed in, please use the popup to signin');
                }
                else if(msg == "ERROR: noInstall")//error handling returned from log.php
                {
                    window.alert('ERROR: chromeGamification install not complete, Please follow the instructions in the popup to resolve the issue');
                }
                else if(msg == "ALERT: badgeAwarded")//alert handling returned from log.php
                {
                    window.alert('You just earned a badge, click the popup for more information');
                }
                else if(msg == "ALERT: badgeAwarded;ALERT: levelUP")//alert handling returned from log.php
                {
                    window.alert("You just leveled up and earned a badge, click the popup for more information");
                }
                else if(msg == "ALERT: levelUP")//alert handling returned from log.php
                {
                    window.alert("You just leveled up, click the popup for more information");
                }
            }
        }
    );
}
function logClick(server){
    var pageURL = document.URL;
    $.ajax(
        {
            type: "post",
            url: server+"/log.php",
            data: "request=logClick&URL=" + pageURL,
            success: function(msg)
            {
                if(msg == "ALERT: badgeAwarded")//alert handling returned from log.php
                {
                    window.alert('You just earned a badge, click the popup for more information');
                }
                else if(msg == "ALERT: badgeAwarded;ALERT: levelUP")//alert handling returned from log.php
                {
                    window.alert("You just leveled up and earned a badge, click the popup for more information");
                }
                else if(msg == "ALERT: levelUP")//alert handling returned from log.php
                {
                    window.alert("You just leveled up, click the popup for more information");
                }
            }
        }
    );
}
function logScrolling(server){
    var pageURL = document.URL;
    $.ajax(
        {
            type: "post",
            url: server+"/log.php",
            data: "request=logScrolling&URL=" + pageURL,success: function(msg)
            {
                if(msg == "ALERT: badgeAwarded")//alert handling returned from log.php
                {
                    window.alert('You just earned a badge, click the popup for more information');
                }
                else if(msg == "ALERT: badgeAwarded;ALERT: levelUP")//alert handling returned from log.php
                {
                    window.alert("You just leveled up and earned a badge, click the popup for more information");
                }
                else if(msg == "ALERT: levelUP")//alert handling returned from log.php
                {
                    window.alert("You just leveled up, click the popup for more information");
                }
            }
        }
    );
}
function logTyping(server){
    var pageURL = document.URL;
    $.ajax(
        {
            type: "post",
            url: server+"/log.php",
            data: "request=logTyping&URL=" + pageURL,success: function(msg)
            {
                if(msg == "ALERT: badgeAwarded")//alert handling returned from log.php
                {
                    window.alert('You just earned a badge, click the popup for more information');
                }
                else if(msg == "ALERT: badgeAwarded;ALERT: levelUP")//alert handling returned from log.php
                {
                    window.alert("You just leveled up and earned a badge, click the popup for more information");
                }
                else if(msg == "ALERT: levelUP")//alert handling returned from log.php
                {
                    window.alert("You just leveled up, click the popup for more information");
                }
            }
        }
    );
}
