<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDao;
use Tunacan\Bundle\DataObject\CardDto;

class CardService implements CardServiceInterface
{
    /** @var CardDao */
    private $cardDao;

    public function __construct(CardDao $cardDao)
    {
        $this->cardDao = $cardDao;
    }

    public function getCardListByBbsUid(string $bbsUid): array
    {
        return $this->cardDao->getCardListByBbsUid($bbsUid);
    }

    public function getCardListByCardUid(int $cardUid): CardDto
    {
        return $this->cardDao->getCardByCardUid($cardUid);
    }

    public function getCardSize(int $cardUid): int
    {
        return $this->cardDao->getCardSize($cardUid);
    }
}
