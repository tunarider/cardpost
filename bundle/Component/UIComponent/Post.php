<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\Bundle\DataObject\PostDTO;
use Tunacan\Bundle\Util\DateTimeBuilder;
use Tunacan\MVC\AbstractComponent;

class Post extends AbstractComponent
{
    protected $htmlTemplateName = 'post';
    /**
     * @Inject("date.format.common")
     * @var string
     */
    private $dateFormat;
    /**
     * @Inject("tmp.domain.image")
     * @var string
     */
    private $imageDomain;
    /**
     * @Inject
     * @var DateTimeBuilder
     */
    private $dateTimeBuilder;
    /** @var PostDTO */
    private $postDTO;

    public function getObject()
    {
        $post = new Post($this->loader, $this->parser);
        $post->setDateFormat($this->dateFormat);
        $post->setDateTimeBuilder($this->dateTimeBuilder);
        $post->setImageDomain($this->imageDomain);
        return $post;
    }

    public function setDateFormat(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    public function setDateTimeBuilder(DateTimeBuilder $dateTimeBuilder)
    {
        $this->dateTimeBuilder = $dateTimeBuilder;
    }

    public function setImageDomain(string $imageDomain)
    {
        $this->imageDomain = $imageDomain;
    }

    public function setPostDTO(PostDTO $postDTO)
    {
        $this->postDTO = $postDTO;
    }

    public function __toString()
    {
        if ($this->postDTO->getStatus() == 1) {
            return $this->parser->parse($this->loader->load($this->htmlTemplateName), [
                'postUID' => $this->postDTO->getPostUID(),
                'order' => $this->postDTO->getOrder(),
                'name' => $this->postDTO->getName(),
                'userID' => $this->postDTO->getUserID(),
                'time' => $this->postDTO->getCreateDate()
                    ->setTimezone($this->dateTimeBuilder->getUserTimezone())
                    ->format($this->dateFormat),
                'content' => $this->postDTO->getContent()
                    ->applyAnchor($this->postDTO->getBbsUID(), $this->postDTO->getCardUID())
                    ->__toString(),
                'image' => $this->getImageWithTag()
            ]);
        } else {
            return '';
        }
    }

    private function getImageWithTag()
    {
        $imageSrc = $this->imageDomain."/".rawurlencode($this->postDTO->getImage());
        $noImageSrc = $this->imageDomain."/no-image.png";
        if ($this->postDTO->getImage()) {
//            var_dump(@getimagesize($imageSrc));
//            var_dump(@get_headers('http://127.0.0.1:8000', 1));
//            if ((@getimagesize($imageSrc) === false)) {
//                return "<img class='thumbnail' src='{$noImageSrc}'/>";
//            } else {
                return "<a href='{$imageSrc}'><img class='thumbnail' src='{$imageSrc}'/></a>";
//            }
        } else {
            return '';
        }
    }
}
