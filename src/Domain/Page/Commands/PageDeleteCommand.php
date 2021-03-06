<?php

namespace Domain\Page\Commands;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PageDeleteCommand
 * @package Commands\Page
 */
class PageDeleteCommand
{
    /**
     * @Assert\Uuid(message="Неверный идетификатор")
     *
     * @var string
     */
    private $pageId;

    /**
     * PageDeleteCommand constructor.
     *
     * @param null|string $pageId
     */
    public function __construct(?string $pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * @return string
     */
    public function getPageId(): string
    {
        return $this->pageId;
    }
}