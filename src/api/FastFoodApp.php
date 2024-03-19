<?php
namespace MsPedidosApp\api;

use GuzzleHttp\Exception\GuzzleException;
use MsPedidosApp\adapters\queue\QueueGateway;
use MsPedidosApp\controllers\base\ControllerBuilder;
use MsPedidosApp\core\exceptions\ServicesException;
use RdKafka\Exception;

class FastFoodApp extends ControllerBuilder
{
    /**
     * @param $repository
     * @throws GuzzleException
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws ServicesException
     * @throws Exception
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
        QueueGateway::listen();
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
