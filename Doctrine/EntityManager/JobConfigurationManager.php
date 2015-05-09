<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\JobQueueBundle\Doctrine\EntityManager;

use Aureja\JobQueue\JobState;
use Aureja\JobQueue\Model\JobConfigurationInterface;
use Aureja\JobQueue\Model\Manager\JobConfigurationManager as BaseJobConfigurationManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 4/24/15 10:00 PM
 */
class JobConfigurationManager extends BaseJobConfigurationManager
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param string $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $em->getClassMetadata($class)->name;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function findByName($name)
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByQueueAndState($queue, $state)
    {
        return $this->repository->findOneBy(['queue' => $queue, 'state' => $state]);
    }

    /**
     * {@inheritdoc}
     */
    public function findPotentialDeadJobs($queue)
    {
        $qb = $this->repository->createQueryBuilder('jc');

        $qb->andWhere($qb->expr()->in('jc.state', ':states'))
            ->setParameter('states', [JobState::STATE_RUNNING, JobState::STATE_FAILED]);
        $qb->andWhere($qb->expr()->eq('jc.enabled', $qb->expr()->literal(1)));
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->isNull('jc.nextStart'),
                $qb->expr()->lte('jc.nextStart', ':now')
            )
        )->setParameter('now', new \DateTime(), Type::DATETIME);

        if (null !== $queue) {
            $qb->andWhere($qb->expr()->eq('jc.queue', ':queue'))
                ->setParameter('queue', $queue);
        }

        $qb->orderBy('jc.orderNr', 'ASC');
        $qb->select('jc');

        return $qb->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findNext($queue)
    {
        $qb = $this->repository->createQueryBuilder('jc');

        $qb->andWhere($qb->expr()->notIn('jc.state', ':states'))
            ->setParameter('states', [JobState::STATE_RUNNING, JobState::STATE_FAILED]);
        $qb->andWhere($qb->expr()->eq('jc.enabled', $qb->expr()->literal(1)));
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->isNull('jc.nextStart'),
                $qb->expr()->lte('jc.nextStart', ':now')
            )
        )->setParameter('now', new \DateTime(), Type::DATETIME);

        if (null !== $queue) {
            $qb->andWhere($qb->expr()->eq('jc.queue', ':queue'))
                ->setParameter('queue', $queue);
        }

        $qb->orderBy('jc.nextStart');
        $qb->setMaxResults(1);

        $qb->select('jc');

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function add(JobConfigurationInterface $configuration, $save = false)
    {
        $this->em->persist($configuration);
        if (true === $save) {
            $this->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(JobConfigurationInterface $configuration, $save = false)
    {
        $this->em->remove($configuration);
        if (true === $save) {
            $this->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->em->clear($this->getClass());
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}
