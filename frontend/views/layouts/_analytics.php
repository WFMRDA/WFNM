<script>
// window.fbAsyncInit = function() {
//     FB.init({
//         appId      : '1393521670974339',
//         xfbml      : true,
//         version    : 'v3.1'
//     });
//
//     // Get Embedded Video Player API Instance
//     var my_video_player;
//     FB.Event.subscribe('xfbml.ready', function(msg) {
//         console.log('fb',msg);
//     });
// };
// (function(d, s, id) {
//     var js, fjs = d.getElementsByTagName(s)[0];
//     if (d.getElementById(id)) return;
//     js = d.createElement(s); js.id = id;
//     js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.1&appId=1393521670974339";
//     fjs.parentNode.insertBefore(js, fjs);
// }(document, 'script', 'facebook-jssdk'));
window.fbAsyncInit = function() {
  FB.init({
    appId            : '1393521670974339',
    autoLogAppEvents : true,
    xfbml            : true,
    version          : 'v3.1'
  });
  console.log('init fb')
};

(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "https://connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));

<?php if(!YII_ENV_DEV) { ?>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-47323194-2', 'auto');
      ga('send', 'pageview');
<?php } ?>
</script>
