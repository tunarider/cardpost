<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\MVC\AbstractComponent;

class CardListNode extends AbstractComponent
{
    protected static $templateName = 'cardListNode';
    /** @var string */
    private $bbsUID;
    /** @var int */
    private $cardUID;
    /** @var int */
    private $order;
    /** @var string */
    private $title;
    /** @var int */
    private $size;
    /** @var string */
    private $owner;
    /** @var \DateTime */
    private $refreshDate;
    /** @var \DateTimeZone */
    private $timezone;
    /** @var string */
    private $dateFormat;

    /**
     * @param string $dateFormat
     */
    public function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @param \DateTimeZone $timezone
     */
    public function setTimezone(\DateTimeZone $timezone): void
    {
        $this->timezone = $timezone;
    }

    /**
     * @param string $bbsUID
     */
    public function setBbsUID(string $bbsUID): void
    {
        $this->bbsUID = $bbsUID;
    }

    /**
     * @param int $cardUID
     */
    public function setCardUID(int $cardUID): void
    {
        $this->cardUID = $cardUID;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @param string $owner
     */
    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @param \DateTime $refreshDate
     */
    public function setRefreshDate(\DateTime $refreshDate): void
    {
        $this->refreshDate = $refreshDate;
    }

    public function __toString()
    {
        // TODO: link 기준 하드링크 수정해야함
        $linkCriteria = 10;
        if ($this->order <= $linkCriteria) {
            $orderLink = "/trace/{$this->bbsUID}/{$this->cardUID}";
            $sizeLink = "{$orderLink}/recent";
            $titleLink = "#card_{$this->order}";
        } else {
            $orderLink = "";
            $sizeLink = "/trace/{$this->bbsUID}/{$this->cardUID}";
            $titleLink = "{$orderLink}/recent";
        }
        return $this->parser->parse($this->template, [
            'order_link' => $orderLink,
            'size_link' => $sizeLink,
            'title_link' => $titleLink,
            'order' => $this->order,
            'title' => $this->title,
            'size' => $this->size,
            'owner' => $this->owner,
            'refresh_date' => $this->refreshDate
                ->setTimezone($this->timezone)
                ->format($this->dateFormat)
        ]);
    }
}