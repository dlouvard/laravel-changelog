<?php namespace Dlouvard\Changelog;

use Dlouvard\Changelog\Exceptions\ChangelogException;

class ChangelogObserver
{

    public function saving($record)
    {
        if ($record->isDirty())
        {
            $record->{$record->getChangeIDColumn()} = \Change::getChangeID();
            \Change::setContextID($record->id);
            if (!$record->{$record->getChangeIDColumn()} && $record->getForceChangeLogging())
            {
                throw new ChangelogException('Cannot save this model outside of a change log (because forceChangeLogging is enabled). Start a new change with Change::begin() first.');
            }
        }
    }

    public function saved($record)
    {
        \Change::setContextID($record->id);
    }

}