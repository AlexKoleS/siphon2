<?php
namespace Icecave\Siphon\Score;

use InvalidArgumentException;

class PeriodScore implements ScoreInterface
{
    use ScoreTrait;

    private function checkScopeType(ScopeInterface $scope)
    {
        if (!$scope instanceof PeriodInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported scope type "%s", expected "%s".',
                    get_class($scope),
                     PeriodInterface::class
                )
            );
        }
    }
}
