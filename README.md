# OpenVPN3Client

OpenVPN3-client is a package to setup a oVPN-tunnel to a server.

## Installation

Install Openvpn3 client on your client server:
```url
https://community.openvpn.net/openvpn/wiki/OpenVPN3Linux
````

Once OpenVPN3 is installed just add the package to your project:

```bash
composer require goudenvis/openvpn3-client
```

Add in your .env file the following variable:
```php
VPN_CLIENT_FOLDER=
```


## Usage
Add the .ovpn file(s) in the located folder.

### Install the added .ovpn configuration files

```bash
php artisan openvpn3-client:add-config {name}
```
### Remove a config
```bash
php artisan openvpn3-client:remove-config {name}
```

### Start a tunnel
```bash
php artisan openvpn3-client:start {name}
```


## To do
This package isn't complete. Feel free to add functionality. If you have found any security issues, please contact me directly.

There is a small to do list:
- Add DCO support
- Extend the configuration
- Add logging in database


## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.



## License

[MIT](https://choosealicense.com/licenses/mit/)
