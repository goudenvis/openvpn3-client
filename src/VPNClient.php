<?php

namespace Goudenvis\OpenVPN3Client;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class VPNClient
{
    /**
     * @param $name
     * @return bool
     *
     *  Open the requested VPN-tunnel
     */
    public static function open($name = null) : bool
    {
        if ($name === null) {
            $name = self::getFileName();
        }

        if (self::configLoaded($name)) {
            return self::sessionStart($name);
        }

        return false;
    }

    public static function close($name = null) : bool
    {
        if ($name === null) {
            $name = self::activeConfig();
        }

        return self::sessionEnd($name);
    }

    public static function closeByPath() : bool
    {
        $path = self::activePath();

        if (Str::contains($path, 'no sessions available')) {
            return false;
        }

        exec("openvpn3 session-manage --path {$path} --disconnect");

        if (self::activeSession())
        {
            self::closeByPath();
        }

        return true;
    }

    public static function addConfig($configName = null) : bool
    {
        $location = env('VPN_CLIENT_FOLDER');

        $files = Storage::files("{$location}/");

        if (count($files) === 0) {
            return false;
        }

        if ($configName) {
            if ( in_array($location . '/' . $configName . '.ovpn', $files)) {
                $path = Storage::path($configName . '.ovpn');
                dump("openvpn3 config-import --config {$path} --name {$configName} --persistent");
                Process::run("openvpn3 config-import --config {$path} --name {$configName} --persistent");
            }
            return true;
        }

        foreach ($files as $file) {
            dump($file);
            $name = Str::beforeLast(Str::afterLast($file, "/"), ".ovpn");

            if ($configName !== null && $configName !== $name) {
                return false;
            }

            $path = Storage::path($file);

            Process::run("openvpn3 config-import --config {$path} --name {$name} --persistent");

            return true;
        }

        return true;
    }

    public static function removeConfig($name = null) : bool
    {
        $result = shell_exec('openvpn3 configs-list');

        if ($name !== null ) {
            if (self::configLoaded($name, false)) {
                exec("openvpn3 config-remove --config {$name} --force");
            }
            return true;
        } else {
            preg_match("[a-zA-Z0-9]{36}",$result, $keys);

            foreach ($keys as $key) {
                exec("openvpn3 config-remove --path /net/openvpn/v3/configuration/{$key} --force");
            }
        }
        return true;
    }

    public static function activePath() : string
    {
        $sessionExec = shell_exec('openvpn3 sessions-list');

        return trim(Str::betweenFirst(strtolower($sessionExec), 'path: ', 'created: '));
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
    }

    public static function activeConfig() : string
    {
        $text = shell_exec('openvpn3 sessions-list');

        $endOfString = Str::contains($text, 'Session') ? 'Session' : 'Status';

        return trim(Str::before(Str::between($text, 'Config name: ', $endOfString), '('));
    }

    public static function refresh($name) : bool
    {

        if (self::activeSessionCheck()) {
            self::close($name);
        }

        sleep(5);

        self::start($name);

        return true;
    }

    public static function activeSession() : string
    {
        $sessionExec = shell_exec('openvpn3 sessions-list');

        $sessionName = trim(Str::between(strtolower($sessionExec), 'session name: ', 'status: '));

        if (strlen($sessionName) > 20) {
            $sessionName = trim(substr($sessionName, 20));
        }

        return $sessionName;
    }

    public static function activeSessionCheck() : bool
    {
        $result = exec('openvpn3 sessions-list');

        return !Str::contains($result, 'No sessions available');
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

    private static function sessionEnd($name) : bool
    {
        if ($name === "No sessions available") {
            return true;
        }

        exec('openvpn3 session-manage --config ' . $name . ' --disconnect');

        sleep(1);

        return true;
    }


    public static function configLoaded($name, $throwException = true) : bool
    {
        $result = shell_exec('openvpn3 configs-list');

        if (!Str::contains($result, $name)) {
            if ($throwException) {
                throw new \Exception('Config is not loaded');}
            else {
                return false;
            }
        }

        return true;
    }
}
