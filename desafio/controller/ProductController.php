<?php 

include_once 'model/Product.php';

class ProductController
{
    private $_params;
    private $tableName = 'products';
    private $product;

    public function __construct($params)
    {        
        $this->_params = $params;
        $this->product = new ProductModel();
    }

    public function post() {
        $id = (array_key_exists('id', $this->_params)) ? $this->_params['id'] : null; 
        unset($this->_params['id']);
        $res['produto'] = $this->product->save(null, $this->_params);
        return $res;
    }
    
    public function get() {
        if (array_key_exists('id', $this->_params)) {
            return $this->product->get( (int) $this->_params['id']);
        } else {
            return $this->product->getAll();
        }
    }

    public function patch() {
        
        $id = (array_key_exists('id', $this->_params)) ? $this->_params['id'] : null; 
        unset($this->_params['id']);
        $res['produto'] = $this->product->save($id, $this->_params);
        return $res;
    }

    public function delete() {
        $id = (array_key_exists('id', $this->_params)) ? $this->_params['id'] : null; 
        try {
            $this->product->delete($id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return null;
    }
}
