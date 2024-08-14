<?php

namespace Goudenvis\OpenVPN3Client\Console;

use Illuminate\Console\Command;
use Goudenvis\OpenVPN3Client\VPNClient;

class OpenCommand extends Command
{
    protected $signature = 'openvpn3-client:open {name?}';

    protected $description = 'Start a VPN tunnel';

    public function handle()
    {
        $this->info('Start tunnel');

        VPNClient::open($this->argument('name'));

        $this->info('Tunnel started');
    }
}
