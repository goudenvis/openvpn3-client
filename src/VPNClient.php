<?php

class VPNClient
{
    protected function open($name = null) : bool
    {
        if ($name === null) {
            $name = self::getName();
        }
    }

    private function getName($item = 1)
    {
        $location = env('VPN_CLIENT_FOLDER');
        $files = Storage::files("{$location}/");


    }
}
