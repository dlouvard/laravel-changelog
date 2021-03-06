<?php

namespace Dlouvard\Changelog;

use Dlouvard\Changelog\Exceptions\ChangelogException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: dlouvard_imac
 * Date: 18/07/2017
 * Time: 10:55
 */
class Change
{
    protected $_change = null;
    protected $_contextID = null;
    protected $_contextModel = null;
    protected $_connection = null;
    protected $_validChange = false;
    protected $_colsaved = null;


    protected function getAuthID()
    {
        return Auth::id();
    }

    /**
     * Returns the ID of the change in progress (or null if there is no change in progress)
     *
     * @return null|int The ID of the change in progress (or null if one does not exist)
     */
    public function getChangeID()
    {
        if ($this->_change)
            return $this->_change->id;

        return null;
    }

    public function validChange()
    {
        $this->_validChange = true;
    }


    /**
     * Begins a change (and optionally a transaction as well)
     *
     * @param string|null $interface A string describing the interface on which this change is taking place. eg. Public, Backend, API, etc.
     * @param string|null $notes A string describing the intended change in more details. eg. "Putting widget in a box", "Placing an order", etc.
     * @param bool $useTransaction Whether or not to begin a transaction as well. Only one transaction exist at a time using this method.
     */
    public function begin(string $interface = null,string $notes = null)
    {
        if ($this->_change)
            throw new ChangelogException('Cannot begin a change because one is already in progress.');
        $this->_change = new ChangeModel();
        $this->_change->user_id = $this->getAuthID();
        $this->_change->interface = $interface;
        $this->_change->notes = $notes;
        $this->_change->status = 'pending';
        //$this->_change->save();
    }

    public function setContextID($id)
    {
        $this->_contextID = $id;
    }

    public function setContextModel($record)
    {
        $this->_contextModel = explode('\\', get_class($record))[2];
    }

    public function setColsaved($note)
    {
        $this->_colsaved = $note;
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
        $this->_change->context_id = $this->_contextID;
        $this->_change->context_model = $this->_contextModel;
        $this->_change->colsaved = $this->_colsaved;
        $this->_change->save();
        if(!$this->_validChange)
            $this->_change->delete();
        $this->_colsaved = null;
        $this->_validChange = false;
        $this->_change = null;
    }
}