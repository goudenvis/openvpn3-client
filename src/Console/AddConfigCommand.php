<?php

namespace Goudenvis\OpenVPN3Client\Console;

use Illuminate\Console\Command;
use Goudenvis\OpenVPN3Client\VPNClient;

class AddConfigCommand extends Command
{
    protected $signature = 'openvpn3-client:add-config {name?}';

    protected $description = 'Add a .ovpn config';

    public function handle()
    {
        $this->info('Add config');

        VPNClient::addConfig($this->argument('name'));

        $this->info('Config added');
    }
}
