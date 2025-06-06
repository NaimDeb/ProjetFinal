<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\FollowedGames;
use App\Entity\Game;
use App\Entity\Patchnote;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FollowedGamesPersister implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): FollowedGames
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        if (!$user) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException('Not authenticated');
        }

        $gameId = $uriVariables['id'] ?? null;
        if ($gameId === null) {
            throw new BadRequestHttpException('Game ID is required');
        }

        $game = $this->entityManager->getRepository(Game::class)->find($gameId);
        if (!$game) {
            throw new NotFoundHttpException('Game not found');
        }

        // Check if the user is already following the game
        $existingFollowedGame = $this->entityManager->getRepository(FollowedGames::class)->findOneBy(['user' => $user, 'game' => $game]);
        if ($existingFollowedGame) {
            throw new BadRequestHttpException('You are already following this game.');
        }

        // If $data is null, instantiate a new FollowedGames
        if (!$data instanceof FollowedGames) {
            $data = new FollowedGames();
        }

        $data->setGame($game);
        $data->setUser($user);

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
