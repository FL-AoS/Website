<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DiscordUser;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class DiscordController
{
    private function tokenRevoke(string $access_token) {
        $curl_tk = curl_init("https://discord.com/api/oauth2/token/revoke");
        curl_setopt($curl_tk, CURLOPT_POST, 1);
        curl_setopt($curl_tk, CURLOPT_USERPWD, env("DISCORD_APP_ID").":".env("DISCORD_APP_SECRET"));
        curl_setopt($curl_tk, CURLOPT_POSTFIELDS, "token=".$access_token);
        curl_setopt($curl_tk, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));

        curl_exec($curl_tk);
        curl_close($curl_tk);
    }

    private function joinDiscord(string $access_token, string $user_id) {
        error_log($user_id);
        error_log($access_token);
        $curl_join = curl_init("https://discord.com/api/guilds/906152369438486579/members/".$user_id);
        curl_setopt($curl_join, CURLOPT_CUSTOMREQUEST, "PUT");

        $body = [
            'access_token' => $access_token
        ];
        $json = json_encode($body);
        curl_setopt($curl_join, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl_join, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bot ".env("DISCORD_BOT_TOKEN")));

        curl_exec($curl_join);
        curl_close($curl_join);
    }

    public function authorize(Request $request)
    {
        $code = $request->input("code");

        $curl_i = curl_init("https://discord.com/api/oauth2/token");
        curl_setopt($curl_i, CURLOPT_POST, 1);
        curl_setopt($curl_i, CURLOPT_USERPWD, env("DISCORD_APP_ID").":".env("DISCORD_APP_SECRET"));
        curl_setopt($curl_i, CURLOPT_POSTFIELDS, "grant_type=authorization_code&code=".$code."&redirect_uri=http://localhost:8000/api/discord/authorization");
        curl_setopt($curl_i, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));

        curl_setopt($curl_i, CURLOPT_RETURNTRANSFER, true);

        $sv_ot = curl_exec($curl_i);
        $res_http_code = curl_getinfo($curl_i, CURLINFO_HTTP_CODE);
        curl_close($curl_i);

        if ($res_http_code != 200)
            return response("", 500);

        $discord_infos = json_decode($sv_ot);
        $scopes = explode(" ", $discord_infos->scope);

        if (!in_array("identify", $scopes, true) || !in_array("guilds.join", $scopes, true)) {
            $this->tokenRevoke($discord_infos->access_token);
            return response("Invalid scopes", 400);
        }

        #

        $curl_gu = curl_init("https://discord.com/api/users/@me");
        curl_setopt($curl_gu, CURLOPT_HTTPHEADER, array("Authorization: ".$discord_infos->token_type." ".$discord_infos->access_token));
        curl_setopt($curl_gu, CURLOPT_RETURNTRANSFER, true);

        $sv = curl_exec($curl_gu);
        $res_user_http_code = curl_getinfo($curl_gu, CURLINFO_HTTP_CODE);
        curl_close($curl_gu);

        if ($res_user_http_code != 200) {
            $this->tokenRevoke($discord_infos->access_token);

            return response("", 500);
        }

        $user_infos = json_decode($sv);
        $this->joinDiscord($discord_infos->access_token, $user_infos->id);

        try {
            $discord_model = DiscordUser::where("discord_id", $user_infos->id)->firstOrFail();
            $user_model = User::where("discord_user_id", $discord_model->id)->firstOrFail();

            $this->tokenRevoke($user_model->discord_access_token);

            $user_model->discord_access_token = $discord_infos->access_token;
            $user_model->discord_token_expire = $discord_infos->expires_in;
            $user_model->discord_refresh_token = $discord_infos->refresh_token;
            $user_model->save();

        } catch (ModelNotFoundException $e) {
            $discord_model = new DiscordUser;
            $discord_model->username = $user_infos->username;
            $discord_model->global_name = $user_infos->global_name;
            $discord_model->discord_id = $user_infos->id;
            $discord_model->avatar_hash = $user_infos->avatar;

            $discord_model->save();

            $user_model = new User;
            $user_model->discord_access_token = $discord_infos->access_token;
            $user_model->discord_token_expire = $discord_infos->expires_in;
            $user_model->discord_refresh_token = $discord_infos->refresh_token;
            $user_model->discord_user_id = $discord_model->id;

            $user_model->save();

        }

        Auth::login($user_model);
        

        return redirect("/");
    }
}
