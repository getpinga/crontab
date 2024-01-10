# Crontab
A crontab with precision in seconds written in PHP based on [workerman](https://github.com/walkor/workerman), [workerman/crontab](https://github.com/walkor/crontab) and [hyperf/crontab](https://github.com/hyperf/crontab).

# Install
```
composer require pinga/crontab
```

# Usage
start.php
```php
<?php
use Workerman\Worker;
require __DIR__ . '/../vendor/autoload.php';

use Pinga\Crontab\Crontab;
$worker = new Worker();

$worker->onWorkerStart = function () {
    // Execute the function in the first second of every minute.
    new Crontab('1 * * * * *', function(){
        echo date('Y-m-d H:i:s')."\n";
    });
};

Worker::runAll();
```

Run with commands `php start.php start` or php `start.php start -d`
