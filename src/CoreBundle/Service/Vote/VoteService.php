<?php
namespace CoreBundle\Service\Vote;

use CoreBundle\Entity\Vote;
use CoreBundle\Entity\Rating;
use CoreBundle\Exception\Vote\VoteAlreadyExistsException;
use CoreBundle\Model\Request\Vote\VoteListRequest;
use CoreBundle\Service\CurrentUser\CurrentUserService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RestBundle\Service\AbstractService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CoreBundle\Model\Request\Vote\VoteCreateRequest;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class VoteService
 *
 * @method Vote createEntity()
 */
class VoteService extends AbstractService
{
    /**
     * @var CurrentUserService
     */
    private  $currentUserService;

    /**
     * VoteService constructor.
     * @param ContainerInterface $container
     * @param string $entityClass
     * @param CurrentUserService $currentUserService
     */
    public function __construct(ContainerInterface $container, string $entityClass, CurrentUserService $currentUserService) {
        parent::__construct($container, $entityClass);
        $this->setContainer($container);
        $this->currentUserService = $currentUserService;
    }

    /**
     * @param VoteCreateRequest $request
     * @return Vote
     */
    public function createVote(VoteCreateRequest $request): Vote
    {
        $currentUser = $this->currentUserService->getCurrentUser();
        try {
            $this->getEntityBy(
                [
                    'user' => $currentUser,
                    'challenge' => $request->getChallenge(),
                ]
            );
            throw new VoteAlreadyExistsException();
        } catch (EntityNotFoundException $e) {
            // we haven't found vote - that's ok
        }

        $vote = $this->createEntity();
        $vote->setChallenge($request->getChallenge());
        $vote->setUser($currentUser);
        $vote->setDate(new \DateTime());
        $this->saveEntity($vote);

        $ratings = new ArrayCollection();
        foreach ($request->getRatings() as $voting) {
            $rating = new Rating();
            $rating->setParticipant($voting->getParticipant());
            $rating->setPlace($voting->getPlace());
            $rating->setVote($vote);
            $ratings->add($rating);
        }

        $vote->setRatings($ratings);
        $this->saveEntity($vote);

        return $vote;
    }

    /**
     * @param VoteListRequest $request
     * @return Collection
     */
    public function getVotes(VoteListRequest $request): Collection
    {
        return $request->getChallenge()->getVotes();
    }
}