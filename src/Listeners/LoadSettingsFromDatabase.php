<?php

namespace WiseClock\Login2See\Listeners;

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Event\PrepareApiAttributes;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;

class LoadSettingsFromDatabase
{
    protected $settings;
    protected $fields = [
        'post',
        'link',
        'image',
    ];

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(PrepareApiAttributes::class, [$this, 'prepareApiAttributes']);
    }

    public function prepareApiAttributes(PrepareApiAttributes $event)
    {
        if ($event->isSerializer(ForumSerializer::class))
        {
            foreach ($this->fields as $field)
            {
                $k = 'wiseclock.login2see.' . $field;
                $event->attributes[$k] = $this->settings->get($k);
            }
        }
    }
}
