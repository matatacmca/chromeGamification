//define server
var server = "https://hostName";//needs to be modified
showProgress(server);
function showProgress(server)
{
    $.ajax(
        {
            type: "post",
            url: server + "/progress.php",
            data: "request=showProgress",
            success: function(msg)
            {
                $('#content').html(msg);//
            }
        }
    );
}
