<?php

declare(strict_types=1);

namespace Questionnaire\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Questionnaire\Domain\Entity\Questionnaire;
use Questionnaire\Domain\Exception\QuestionnaireNotFoundException;
use Questionnaire\Domain\QuestionnaireRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<Questionnaire>
 */
class QuestionnaireRepository extends ServiceEntityRepository implements QuestionnaireRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questionnaire::class);
    }

    public function get(UuidInterface $id): Questionnaire
    {
        return $this->findOneBy(['id' => $id])
            ?? throw new QuestionnaireNotFoundException(\sprintf('Questionnaire not found id: %s', $id));
    }

    public function save(Questionnaire $questionnaire): void
    {
        $this->getEntityManager()->persist($questionnaire);
        $this->getEntityManager()->flush();
    }
}
