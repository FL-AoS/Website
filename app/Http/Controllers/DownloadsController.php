<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DownloadsController
{
    public function downloadParkourDemo(string $name) {

        if (Auth::check()) {
            $file = env("PARKOUR_DEMOS_DOWNLOAD_FOLDER")."/".$name;
            return response()->download($file);
        }

        return "You should be login to download files.";
    }
}
