<?php

namespace App\Taggy;

use App\Taggy\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait TaggableTrait
{
  public function tags()
  {
    return $this->morphToMany(Tag::class, 'taggable');
  }

  public function tag($tags)
  {
    dd($this->getWorkableTags($tags));
  }

  private function getWorkableTags($tags)
  {
    if (is_array($tags)) {
      return $this->getTagModels($tags);
    }

    if ($tags instanceof Model) {
      return $this->getTagModels([$tags->slug]);
    }

    return $tags;
  }

  private function filterTagsCollection(Collection $tags)
  {
    return $tags->filter(function ($tag) {
      return $tag instanceof Model;
    });
  }

  private function getTagModels(array $tags)
  {
    return Tag::whereIn('slug', $this->normaliseTagNames($tags))->get();
  }

  private function normaliseTagNames(array $tags)
  {
    return array_map(function ($tag) {
      return str_slug($tag);
    }, $tags);
  }
}
