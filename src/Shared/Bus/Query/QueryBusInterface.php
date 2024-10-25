<?php

declare(strict_types=1);

namespace Shared\Bus\Query;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $query): mixed;
}
