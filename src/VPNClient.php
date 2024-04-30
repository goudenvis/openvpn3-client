<?php

namespace Goudenvis\OpenVPN3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VPNClient
{
    public static function open($name = null) : bool
    {
        if ($name === null) {
            $name = self::getFileName();
        }
        return self::sessionStart($name);
    }

    private static function getFileName($item = null)
    {
        $location = env('VPN_CLIENT_FOLDER');
        $files = Storage::files("{$location}/");
        foreach ($files as $file) {
            $newFiles[] = Str::beforeLast(Str::afterLast($file, "/"), ".ovpn");
        }

        if ($item != null) {
            return [$newFiles[$item-1]];
        }

        return $newFiles;
        dd($newFiles);

    }

    private static function sessionStart($name, $publicAccess = true) : bool
    {
        exec('openvpn3 session-start --config ' . $name);

        if ($publicAccess) {
            sleep(1);
            exec('openvpn3 session-acl --config ' . $name . ' --public-access true');
        }
        sleep (1);

        return true;
    }

}
