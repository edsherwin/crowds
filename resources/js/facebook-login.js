$(function() {
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '568991763714607',
      cookie     : true,
      xfbml      : true,
      version    : 'v6.0'
    });
      
    FB.AppEvents.logPageView();  
  };

});
  

function checkLoginState() {
  FB.getLoginStatus(function(response) {
    console.log('resto: ', response);
    if (response.status === 'connected') {
    
      FB.api(
        '/me/picture',
        'GET',
        {"redirect":"false", "type": "large"},
        function(response) {
          console.log(response);
          if (response.data) {
            $('#_fb_profile_pic').val(response.data.url);
            $('#step-two-form').trigger('submit');
          }
        }
      );
    }
  });
}
