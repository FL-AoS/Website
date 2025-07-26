<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
<body>
    @if(!isset(auth()->user()->player_id))
        <P>PLEASE CREATE AN IN-GAME LOGIN</P>
    @endif
    @if($errors->any())
         <div id="error_message">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
         </div>
    @endif
    <form action="/api/update_login" method="POST">
        @csrf
        <label for="config_login">Login:</label><br>
        <input id="config_login" type="text" name="login" value="{{old("login")}}"><br>
        <label for="config_password">Password:</label><br>
        <input id="config_password" type="password" name="password"><br>
        <input type="submit" value="Save">
    </form>
    <input id="show_password" type="checkbox" onclick="showpassword()">
    <label for="show_password">Show Password</label>

    <script type="text/javascript">
        let cfg_log = document.getElementById("config_login");
        let cfg_pwd = document.getElementById("config_password");

        @if(isset(auth()->user()->player_id))
            cfg_log.value = "{{auth()->user()->player->login}}";
            cfg_pwd.value = "{{auth()->user()->player->password}}";
        @endif

        function showpassword() {
            if (cfg_pwd.type == "password")
                cfg_pwd.type = "text"
            else
                cfg_pwd.type = "password"
        }
    </script>
</body>
</html>
