<?php
namespace App\Data;

use App\Entity\Poste;

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
     * @var Poste[]
     */
    public $poste = [];

}