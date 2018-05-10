<?php

namespace App\Entity;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

/**
 * Class Page
 * @package App\Entity
 */
class Page
{
    /**
     * @var string guid
     */
    private $pageId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var Carbon
     */
    private $createdAt;

    /**
     * @var Carbon
     */
    private $updatedAt;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->pageId = Uuid::uuid4();
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
    }

    /**
     * @return string
     */
    public function getPageId(): string
    {
        return $this->pageId;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}