<?php
namespace App\Data;

class SearchData
{

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = '';
    /**
     * @var string
     */
    public $Region = '';

    /**
     * @var string
     */
    public $TypeVehicule = '';

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

}