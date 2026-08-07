<?php

require __DIR__ . '/vendor/autoload.php';

use Fatih\Data\Admin;

$user = new Admin();

$user->logAdmin();

if(($user->verif) === 'y'){
    $user->admin();
    if(($user->password) === '12'){
        $user->Dashboard();
    }
}else if (($user->verif) === 'n'){
    echo $user->sayHello();
}else{
    echo "...";
}
