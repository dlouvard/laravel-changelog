<?php
/**
 * Created by PhpStorm.
 * User: dlouvard_imac
 * Date: 18/07/2017
 * Time: 11:02
 */

namespace Dlouvard\Changelog;


use Illuminate\Database\Eloquent\Model;

class ChangeModel extends Model
{
    protected $table = 'changes';

    //attributes that are mass assignable...
    protected $fillable = [];
}