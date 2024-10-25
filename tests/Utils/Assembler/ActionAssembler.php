<?php

declare(strict_types=1);

namespace Tests\Utils\Assembler;

use Questionnaire\Domain\Entity\Action;
use Questionnaire\Domain\Entity\ActionType;
use Questionnaire\Domain\Entity\Answer;
use Questionnaire\Domain\Entity\Question;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ActionAssembler
{
    public const string DEFAULT_ID = '5f59452a-c788-4afc-b59c-43d6fff8d674';

    private UuidInterface $id;

    private Answer $answer;

    private ?Question $question = null;

    private ActionType $actionType = ActionType::INCLUDE_PRODUCTS;

    /**
     * @var string[]
     */
    private array $products = [];

    public function __construct()
    {
        $this->id = Uuid::fromString(self::DEFAULT_ID);
    }

    public function assemble(): Action
    {
        return new Action(
            $this->id,
            $this->answer,
            $this->actionType,
            $this->question,
            $this->products,
        );
    }

    public function withAnswer(Answer $answer): ActionAssembler
    {
        $this->answer = $answer;

        return $this;
    }

    public function withId(UuidInterface $id): ActionAssembler
    {
        $this->id = $id;

        return $this;
    }

    public function withQuestion(Question $question): ActionAssembler
    {
        $this->question = $question;

        return $this;
    }

    public function withActionType(ActionType $actionType): ActionAssembler
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * @param string[] $products
     */
    public function withProducts(array $products): ActionAssembler
    {
        $this->products = $products;

        return $this;
    }
}
