<?php

namespace Goudenvis\OpenVPN3Client\Console;

use Illuminate\Console\Command;
use Goudenvis\OpenVPN3Client\VPNClient;

class RemoveConfigCommand extends Command
{
    protected $signature = 'openvpn3-client:remove-config {name?}';

    protected $description = 'Remove a .ovpn config';

    public function handle()
    {
        $this->info('Remove config');

        VPNClient::removeConfig($this->argument('name'));

        $this->info('Config removed');
    }
}
