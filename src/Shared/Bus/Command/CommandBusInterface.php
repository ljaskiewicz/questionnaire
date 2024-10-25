<?php

declare(strict_types=1);

namespace Shared\Bus\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
