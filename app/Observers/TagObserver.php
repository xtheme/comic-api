<?php

namespace App\Observers;

use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;

class TagObserver
{
    /**
     * Handle the Tag "created" event.
     *
     * @param  Tag  $tag
     * @return void
     */
    public function created(Tag $tag)
    {
        //
    }

    /**
     * Handle the Tag "updated" event.
     *
     * @param  Tag  $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        if ($tag->isDirty('name')) {
            $tag->slug = mb_strtolower($tag->name, 'UTF-8');
            $tag->saveQuietly(); // 不重新触发监听事件

            // Tag 异动时同步调整 Tagged 关联的标签
            Tagged::where('tag_name', $tag->getOriginal('name'))->update(
                [
                    'tag_name' => $tag->name,
                    'tag_slug' => mb_strtolower($tag->name, 'UTF-8')
                ]
            );
        }
    }

    /**
     * Handle the Tag "deleted" event.
     *
     * @param  Tag  $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {
        //
    }

    /**
     * Handle the Tag "restored" event.
     *
     * @param  Tag  $tag
     * @return void
     */
    public function restored(Tag $tag)
    {
        //
    }

    /**
     * Handle the Tag "force deleted" event.
     *
     * @param  Tag  $tag
     * @return void
     */
    public function forceDeleted(Tag $tag)
    {
        //
    }
}
