# cplt
Lightweight autocomplete container.
Default interaction is implemented via sockets.

## Usage demo
#### Prerequisites:

 - `php` is installed and available as `cli` command
 - `composer` is available globaly
 - port `7082` is not used

#### Steps:
```bash
wget https://github.com/Mufuphlex/cplt/archive/master.zip
unzip master.zip
cd cplt-master
composer install --no-dev
cd demo
php initiator.php 1>cplt.log 2>cplt.log &
php requester.php 'sta'
```
Output will be like the following:
```php
array(3) {
  'r' =>
  array(5) {
    [0] =>
    string(7) "started"
    [1] =>
    string(8) "standing"
    [2] =>
    string(6) "stairs"
    [3] =>
    string(9) "staggered"
    [4] =>
    string(5) "state"
  }
  'e' =>
  NULL
  'p' =>
  string(36) "sta	5	0	0.0003509521484375	1 408 304"
}
```
