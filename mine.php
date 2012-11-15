<script>
function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    return out;
}

function fbLogin()
{
    FB.login(function(response) {
        if (response.authResponse) {
            FB.api('/me', function(response) {
                $('#loginStatus').html('Good to see you, ' + response.name + '.');
                displayMine();
            });
        } else {
            $('#loginStatus').html('User cancelled login or did not fully authorize.');
        }
    }, {scope: 'read_stream'});
}

function checkPerms() {
    FB.api('/me/permissions', function(perms_response) {
        if(perms_response['data'][0]['read_stream']) {
            displayMine();
        } else {
            fbLogin();
        }
    });
}

function displayMine() {
    FB.api('/me/home', function(response) {
        if (!response || response.error) {
            $('#news').html('Error:'+response.error.code);
        } else {
            var data = response.data;
            var output = '<h2>Newsfeed</h2><ol>';
            for(var i=0; i<data.length; i++) {
                output += '<li>' + data[i].message + "</li>\n"; 
            }
            output += '</ol><h2>Your Posts</h2><ol>';
            FB.api('/me/posts', function(response) {
                if (!response || response.error) {
                    $('#news').html('Error:'+response.error.code);
                } else {
                    var data = response.data;
                    for(var i=0; i<data.length; i++) {
                        output += '<li>' + data[i].message + "</li>\n"; 
                    }
                }
                output += '</ol>';

                $('#news').html(output);            
            });            
        }
    });
}

FBC.callback = function() {
    
    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            // the user is logged in and has authenticated your
            // app, and response.authResponse supplies
            // the user's ID, a valid access token, a signed
            // request, and the time the access token 
            // and signed request each expire
            var uid = response.authResponse.userID;
            var accessToken = response.authResponse.accessToken;
            
            FB.api('/me', function(response) {
                $('#loginStatus').html('Good to see you, ' + response.name + '.');
                checkPerms()
            });
        } else {
            // the user isn't logged in to Facebook.
            $('#loginStatus').html('Not logged into Facebook.');
            fbLogin();
        }
    });
}
</script>

<div id="loginStatus"></div>
<div id="news"><img src="img/indicator.gif" /></div>
