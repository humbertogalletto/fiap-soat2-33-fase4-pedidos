<?php
namespace MsPedidosApp\api;

use MsPedidosApp\controllers\base\ControllerBuilder;

class FastFoodApp extends ControllerBuilder
{
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function create($controller, $request)
    {
        return ($this->buildControllers($controller))->create($request);
    }
    
    public function update($controller, $id, $request)
    {
        return ($this->buildControllers($controller))->update($id, $request);
    }
    
    public function delete($controller, $id)
    {
        return ($this->buildControllers($controller))->delete($id);
    }
    
    public function show($controller, $id)
    {
        return ($this->buildControllers($controller))->show($id);
    }

    public function list($controller, $params = null)
    {
        return ($this->buildControllers($controller))->list($params);
    }

    public function query($controller, $params, $method)
    {
        return ($this->buildControllers($controller))->$method($params);
    }

}