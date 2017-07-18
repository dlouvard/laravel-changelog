<?php
/**
 * Created by PhpStorm.
 * User: dlouvard_imac
 * Date: 18/07/2017
 * Time: 11:16
 */

namespace DLouvard\Changelog;


trait Changelog
{
    /********************************************************************************
     * Overridable Options
     * Set a protected (non-static) property without the _changelog_ prefix to override.
     ********************************************************************************/

    protected static $_changelog_forceChangelogging = true;
    protected static $_changelog_changeIDColumn = 'change_id';


    /********************************************************************************
     * Option Getters
     ********************************************************************************/

    public function getForceChangeLogging()
    {
        return isset($this->forceChangeLogging) ? $this->forceChangeLogging : static::$_changelog_forceChangelogging;
    }

    public function getChangeIDColumn()
    {
        return isset($this->changeIDColumn) ? $this->changeIDColumn : static::$_changelog_changeIDColumn;
    }


    /********************************************************************************
     * Relationships
     ********************************************************************************/

    public function change()
    {
        return $this->belongsTo(\Dlouvard\Changelog\ChangeModel::class);
    }



    /********************************************************************************
     * Method Overrides
     ********************************************************************************/

    /**
     * Add the global scope
     */
    public static function bootChangelog()
    {
        //static::addGlobalScope(new Scopes\TemporalScope);
        static::observe(ChangelogObserver::class);
    }


    /********************************************************************************
     * New Methods
     ********************************************************************************/


    /********************************************************************************
     * Static Methods
     ********************************************************************************/
}