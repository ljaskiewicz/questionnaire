<?php

declare(strict_types=1);

namespace Shared\Bus\Query;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

readonly class QueryBus implements QueryBusInterface
{
    public function __construct(
        private MessageBusInterface $queryBus,
        private LoggerInterface $logger,
    ) {
    }

    public function dispatch(QueryInterface $query): mixed
    {
        try {
            $envelope = $this->queryBus->dispatch($query);
            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (HandlerFailedException $e) {
            $this->logger->error(
                'Message {name} failed. Message: {message}',
                [
                    'name' => \get_class($query),
                    'message' => $e->getMessage(),
                ]
            );

            throw new QueryBusException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
