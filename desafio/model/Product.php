<?php
include_once 'AbstractModel.php';

class ProductModel extends AbstractModel
{
    private $conn;
    protected $tableName = "products";

    public function __construct()
    {
        parent::__construct();
    }
}
