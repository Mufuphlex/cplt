# cplt
Lightweight autocomplete container.
Default interaction is implemented via sockets.

## Usage demo
#### Prerequisites:

 - `composer` is available globaly
 - port `7082` is not used

#### Steps
```bash
wget repo
cd cplt
composer install --no-dev
cd demo
php initiator.php 1>cplt.log 2>cplt.log &
php requester.php 'lo'
```
