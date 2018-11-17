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

    public function getCardListByBbsUid(string $bbsUid, int $page = 1, int $limitCount = 10): array
    {
        $page = ($page < 1)? 1 : $page;
        $startFrom = ($page - 1) * $limitCount;
        return $this->cardDao->getCardListByBbsUid($bbsUid, $startFrom, $limitCount);
    }

    public function getCardByCardUid(int $cardUid): CardDto
    {
        return $this->cardDao->getCardByCardUid($cardUid);
    }

    public function getCardDataOnlyByCardUid(int $cardUid): CardDto
    {
        return $this->cardDao->getCardDataOnlyByCardUid($cardUid);
    }

    /**
     * @param int $cardUid
     * @return int
     * @throws \Exception
     */
    public function getCardSize(int $cardUid): int
    {
        try {
            return $this->cardDao->getCardSize($cardUid);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
