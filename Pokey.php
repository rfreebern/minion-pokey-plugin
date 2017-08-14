<?php

namespace Minion\Plugins;

require 'vendor/autoload.php';

$Pokey = new \Minion\Plugin(
    'Pokey',
    'Pokey for minions.',
    'Ryan N. Freebern / ryan@freebern.org'
);

return $Pokey

->on('PRIVMSG', function ($data) use ($Pokey) {
    list ($command, $arguments) = $Pokey->simpleCommand($data);
    if ($command == 'pokey') {
        $target = $data['arguments'][0];
        if ($target == $Pokey->Minion->State['Nickname']) {
                list ($target, $ident) = explode('!', $data['source']);
        }
        if (count($arguments)) {
            $results = \Pokey\API::search(implode(' ', $arguments));
            if (count($results)) {
                $index = mt_rand(0, count($results) - 1);
                if ($Pokey->conf('Result') == 'First') {
                    $index = 0;
                }
                $result = $results[$index];
                $message = [];
                if ($Pokey->conf('Title')) {
                    $message[] = $result['title'];
                }
                if ($Pokey->conf('Link')) {
                    $message[] = $result['link'];
                }
                if ($Pokey->conf('Image')) {
                    $message[] = $result['image'];
                }
                if ($Pokey->conf('Text')) {
                    $message[] = $result['text'];
                }
                $message = implode("\n", $message);
                if (strlen($message)) {
                    $Pokey->Minion->msg($message, $target);
                } else {
                    $Pokey->Minion->msg($result['text'], $target);
                }
            } else {
                $Pokey->Minion->msg('Nothing found.', $target);
            }
        } else {
            $Pokey->Minion->msg('http://yellow5.com/pokey', $target);
        }
    }
});
