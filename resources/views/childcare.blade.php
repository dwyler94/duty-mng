<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>


  <link rel="stylesheet" href="{{ mix('/childcare/css/app.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

</div>
{{-- ./wrapper --}}
<script src="{{ mix('/childcare/js/app.js') }}"></script>
</body>
<script>
    function idleLogout() {
        var t;
        window.onload = resetTimer;
        window.onmousemove = resetTimer;
        window.onmousedown = resetTimer;  // catches touchscreen presses as well
        window.ontouchstart = resetTimer; // catches touchscreen swipes as well
        window.ontouchmove = resetTimer;  // required by some devices
        window.onclick = resetTimer;      // catches touchpad clicks as well
        window.onkeydown = resetTimer;
        window.addEventListener('scroll', resetTimer, true); // improved; see comments

        function yourFunction() {
            // your function for too long inactivity goes here
            // e.g. window.location.href = 'logout.php';
            localStorage.removeItem('lks_session');
            window.location.href = '/login';
        }

        function resetTimer() {
            clearTimeout(t);
            t = setTimeout(yourFunction, 1000*60*60);  // time is in milliseconds
        }
    }
idleLogout();
</script>
</html>
