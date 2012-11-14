<div id="loginStatus"></div>

<script>
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
                doPost();
            });
        } else {
            // the user isn't logged in to Facebook.
            $('#loginStatus').html('Not logged into Facebook.');
            
            FB.login(function(response) {
                if (response.authResponse) {
                    FB.api('/me', function(response) {
                        $('#loginStatus').html('Good to see you, ' + response.name + '.');
                        doPost();
                    });
                } else {
                    $('#loginStatus').html('User cancelled login or did not fully authorize.');
                }
            });
        }
    });
}
</script>
