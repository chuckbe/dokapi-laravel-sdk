<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class Webhook extends BaseResource
{
    /**
     * The message when a webhook is removed.
     *
     * @var string
     */
    public $message;

    /**
     * The webhook ID in the database of Dokapi.
     *
     * @var string
     */
    public $ulid;

    /**
     * The webhook client ID in the database of Dokapi.
     *
     * @var string
     */
    public $clientUlid;

    /**
     * The timestamp when the webhook was created.
     *
     * @var string
     */
    public $creationTimestamp;

    /**
     * The timestamp when the webhook was last modified.
     *
     * @var string
     */
    public $lastModifiedTimestamp;

    /**
     * The url of the webhook.
     *
     * @var string
     */
    public $url;

    /**
     * The events that the webhook is subscribed to.
     *
     * @var array<int, string>
     */
    public $events;
}
