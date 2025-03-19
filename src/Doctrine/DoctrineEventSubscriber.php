<?php
// src/Doctrine/DoctrineEventSubscriber.php

namespace App\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Events;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Event\ConnectionEventArgs;

class DoctrineEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        // El evento correcto es onConnect
        return [
            Events::onConnect,
        ];
    }

    public function onConnect(ConnectionEventArgs $args)
    {
        $conn = $args->getConnection();
        // Asegúrate de que la conexión es una instancia de DBAL Connection
        if ($conn instanceof DriverConnection) {
            // Establecer la zona horaria de Guatemala
            $conn->executeStatement('SET time_zone = "America/Guatemala"');
        }
    }
}
