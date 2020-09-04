<?php

namespace App\Service;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

/**
 * @property-read \App\Model\CalendarModel $calendar
 * @property-read \App\Model\NewModel $new
 * @property-read \App\Model\GalleryModel $gallery
 * @property-read \App\Model\ImageModel $image
 */
class ProjectModelRepository extends ModelRepository
{

    public function findPublicNews(int $limit, int $offset): Selection
    {
        return $this->new->getTable()->where('public', 1)->order('id DESC')->limit($limit, $offset);
    }

    public function getPublicNewsCount(): int
    {
        return $this->new->getTable()->where('public', 1)->count();
    }
}
