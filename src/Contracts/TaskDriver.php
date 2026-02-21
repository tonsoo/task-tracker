<?php

namespace Tonsoo\TaskTracker\Contracts;

interface TaskDriver
{
    public function makeManager(array $config): TaskManager;
}
