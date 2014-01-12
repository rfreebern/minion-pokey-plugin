<?php

namespace Minion\Plugins;

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
                $result = array_shift($results);
                $Pokey->Minion->msg($result['text'], $target);
            } else {
                $Pokey->Minion->msg('Nothing found.', $target);
            }
        } else {
            $Pokey->Minion->msg('http://yellow5.com/pokey', $target);
        }
    }
});
