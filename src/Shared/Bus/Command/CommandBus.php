<?php

declare(strict_types=1);

namespace Shared\Bus\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private LoggerInterface $logger,
    ) {
    }

    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $this->logger->error(
                'Message {name} failed. Message:  {message}',
                [
                    'name' => \get_class($command),
                    'message' => $e->getMessage(),
                ]
            );
            $originalException = $e->getPrevious();

            throw new CommandBusException($originalException ? $originalException->getMessage() : $e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
