<?php

namespace DLouvard\Changelog;

use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: dlouvard_imac
 * Date: 18/07/2017
 * Time: 10:55
 */
class Change
{
    protected $_change = null;

    protected function getAuthID()
    {
        return Auth::id();
    }

    /**
     * Begins a change (and optionally a transaction as well)
     *
     * @param string|null $interface A string describing the interface on which this change is taking place. eg. Public, Backend, API, etc.
     * @param string|null $notes A string describing the intended change in more details. eg. "Putting widget in a box", "Placing an order", etc.
     * @param bool $useTransaction Whether or not to begin a transaction as well. Only one transaction exist at a time using this method.
     */
    public function begin(string $interface = null, string $notes = null, $useTransaction = true)
    {
        if ($this->_change)
            throw new ChangelogException('Cannot begin a change because one is already in progress.');


        $this->_change = new ChangeModel();
        $this->_change->user_id = $this->getAuthID();
        $this->_change->interface = $interface;
        $this->_change->notes = $notes;
        $this->_change->status = 'pending';
        $this->_change->save();

        if ($useTransaction)
        {
            DB::connection($this->_connection)->beginTransaction();
            $this->_inTransaction = true;
        }
    }

    /**
     * Commits a change that was started with Change::begin() and marks it as successful. If a transaction was started, it is committed as well.
     *
     * @throws ChangelogException If no change is in progress to commit.
     */
    public function commit()
    {
        if (!$this->_change)
            throw new ChangelogException('Cannot commit a change because there is no change in progress');

        $this->_change->status = 'complete';
        $this->_change->save();

        if ($this->_inTransaction)
        {
            DB::connection($this->_connection)->commit();
            $this->_inTransaction = false;
        }

        $this->_change = null;
    }
}