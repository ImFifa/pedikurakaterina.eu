<?php

namespace App\Model;

use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class CalendarModel extends BaseModel
{

    protected $table = 'calendar';

    public function getCalendar($slug): ?ActiveRow
    {
        return $this->getTable()->where('slug', $slug)->fetch();
    }
}
