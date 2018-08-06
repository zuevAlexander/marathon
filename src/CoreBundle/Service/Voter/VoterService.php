<?php
namespace CoreBundle\Service\Voter;

use CoreBundle\Entity\Vote;
use CoreBundle\Entity\Voter;
use CoreBundle\Exception\Voter\VoterAlreadyExistsException;
use Doctrine\Common\Collections\ArrayCollection;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CoreBundle\Model\Request\Voter\VoterCreateRequest;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class VoterService
 *
 * @method Voter createEntity()
 */
class VoterService extends AbstractService
{
    /**
     * VoterService constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     */
    public function __construct(ContainerInterface $container, string $entityClass
    ) {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
    }

    /**
     * @param VoterCreateRequest $request
     * @return Voter
     */
    public function createVoter(VoterCreateRequest $request): Voter
    {
        try {
            $this->getEntityBy(['votingEmail' => $request->getVotingEmail()]);
            throw new VoterAlreadyExistsException();
        } catch (EntityNotFoundException $e) {
            // we haven't found voter - that's ok
        }

        $voter = $this->createEntity();
        $voter->setVotingName($request->getVotingName());
        $voter->setVotingEmail($request->getVotingEmail());
        $this->saveEntity($voter);

        $votes = new ArrayCollection();
        foreach ($request->getVotes() as $voteArr) {
            $vote = new Vote();
            $vote->setUser($voteArr->getUser());
            $vote->setPlace($voteArr->getPlace());
            $vote->setVoter($voter);
            $votes->add($vote);
        }

        $voter->setVotes($votes);
        $this->saveEntity($voter);

        return $voter;
    }
}