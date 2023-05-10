<?php

namespace App;

interface ConnectionAwareInterface
{
    public function setConnection(\PDO $connection);
}