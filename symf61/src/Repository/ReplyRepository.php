<?php

namespace App\Repository;

use App\Entity\Reply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reply>
 *
 * @method Reply|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reply|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reply[]    findAll()
 * @method Reply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reply::class);
    }

    public function add(Reply $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reply $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public static function getReport(ManagerRegistry $doctrine): array
    {
        $replies = $doctrine->getConnection()->fetchAllAssociative('SELECT
                variant.question_id,
                question.`text` as question_text,
                reply.variant_id,
                variant.`text` as variant_text,
                reply.id as reply_id
            FROM
                question
            LEFT JOIN
                variant ON (question.id = variant.question_id)
            LEFT JOIN 
                reply ON (variant.id = reply.variant_id)');

        foreach ($replies as $reply) {
            if (isset($report[$reply['question_text']])) {
                if (array_key_exists($reply['variant_text'], $report[$reply['question_text']])) {
                    ++$report[$reply['question_text']][$reply['variant_text']];
                } else {
                    if ($reply['reply_id'] !== null) {
                        $report[$reply['question_text']][$reply['variant_text']] = 1;
                    } else {
                        $report[$reply['question_text']][$reply['variant_text']] = 0;
                    }
                }
            } else {
                if ($reply['reply_id'] !== null) {
                    $report[$reply['question_text']][$reply['variant_text']] = 1;
                } else {
                    $report[$reply['question_text']][$reply['variant_text']] = 0;
                }
            }
        }
        
        return $report;
    }
}
