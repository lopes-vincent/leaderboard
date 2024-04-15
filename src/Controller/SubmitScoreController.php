<?php
namespace App\Controller;

use App\Entity\Score;
use App\Repository\GameRepository;
use App\Repository\ScoreRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SubmitScoreController
{
    public function __invoke(
        Request $request,
        Score $score,
        GameRepository $gameRepository,
        ScoreRepository $scoreRepository,
        EntityManagerInterface $entityManager
    ): Score
    {
        $game = $gameRepository->findOneBy(['code' => $request->get('gameCode')]);
        $hash = strtoupper(hash('sha256', $score->getName().$score->getScore().$game->getApiKey()));
        if (!hash_equals($score->getHash(), $hash)) {
            $debug = [
                'name' => $score->getName(),
                'score' => $score->getScore(),
                'hash' => $score->getHash(),
                'trueHash' => $hash
            ];
            throw new \Exception(json_encode($debug));
        }
        
        $score->setGame($game);
        
        $entityManager->persist($score);
        $entityManager->flush();
        
        $criteria = new Criteria();
        $where = $score->getDirection() === "asc" ? Criteria::expr()->lt('score', $score->getScore()) : Criteria::expr()->gt('score', $score->getScore());
        $criteria->where($where);
        $count = $scoreRepository->matching($criteria)->count();
        $score->setPosition($count + 1);
        
        return $score;
    }
}
