<?php

namespace Tonso\TaskTracker\Contracts;

interface TaskDriver
{
    public function makeManager(array $config): TaskManager;
}
