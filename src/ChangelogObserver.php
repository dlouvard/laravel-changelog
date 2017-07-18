<?php namespace Dlouvard\Changelog;

use Dlouvard\Changelog\Exceptions\ChangelogException;

class ChangelogObserver
{

    public function saving($record)
    {
        if ($record->isDirty()) {
            if ($record->id):
                \Change::begin("Update");
                \Change::setColsaved(serialize($record->getDirty()));
            else:
                \Change::begin("Create");
                if ($record->refColumnDelete):
                    foreach ($record->refColumnDelete as $col):
                        $data[$col] = $record->{$col};
                    endforeach;
                    \Change::setColsaved(serialize($data));
                endif;
            endif;
           // $record->{$record->getChangeIDColumn()} = \Change::getChangeID();
            \Change::setContextID($record->id);
            \Change::validChange();

        }
    }

    public function saved($record)
    {
        if ($record->isDirty()):
            \Change::setContextModel($record);
            \Change::setContextID($record->id);
            \Change::commit();
        endif;
    }

    public
    function deleting($record)
    {
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
    }

    public
    function deleted($record)
    {
        \Change::commit();
    }
}