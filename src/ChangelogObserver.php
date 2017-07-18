<?php namespace Dlouvard\Changelog;

use Dlouvard\Changelog\Exceptions\ChangelogException;

class ChangelogObserver
{

    public function saving($record)
    {
        if ($record->isDirty() && auth()->check()) {
            if ($record->id):
                \Change::begin("Update");
                \Change::setColsaved(serialize($record->getDirty()));
                \Change::setContextID($record->id);
                \Change::validChange();
            else:
                \Change::begin("Create");
                if ($record->refColumnDelete && !$record->_changeSecondary):
                    foreach ($record->refColumnDelete as $col):
                        $data[$col] = $record->{$col};
                    endforeach;
                    \Change::setColsaved(serialize($data));
                    \Change::validChange();
                endif;
            endif;
            // $record->{$record->getChangeIDColumn()} = \Change::getChangeID();

        }
    }

    public function saved($record)
    {
        if ($record->isDirty() && auth()->check()):
            \Change::setContextModel($record);
            \Change::setContextID($record->id);
            \Change::commit();
        endif;
    }

    public function deleting($record)
    {
        if (auth()->check()):
            \Change::begin("Delete");
            if (!$record->refColumnDelete)
                throw new ChangelogException('No column referenced');

            foreach ($record->refColumnDelete as $col):
                $data[$col] = $record->{$col};
            endforeach;
            \Change::setColsaved(serialize($data));
            \Change::setContextModel($record);
            \Change::getChangeID();
            \Change::setContextID($record->id);
            \Change::validChange();
        endif;
    }

    public function deleted($record)
    {
        if (auth()->check()):
            \Change::commit();
        endif;
    }
}