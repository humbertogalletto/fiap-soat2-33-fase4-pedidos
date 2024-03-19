<?php

namespace MsPedidosApp\core\services;

use MongoDB\Driver\Exception\Exception;
use MsPedidosApp\adapters\db\external\MongoRepository;
use MsPedidosApp\core\entities\Cliente;
use MsPedidosApp\core\exceptions\ServicesException;
use MsPedidosApp\core\interfaces\IClienteService;
use MsPedidosApp\core\interfaces\IPedidoService;

class ClienteService implements IClienteService
{

    protected Cliente $entity;

    protected string $namespace = 'cliente';
    protected string $pKey = 'oid';

    protected MongoRepository $repository;

    /**
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
        $this->repository->setNamespace($this->namespace);
        $this->repository->setPKey($this->pKey);
        $this->entity = new Cliente();
    }

    /**
     * @param array $request
     * @return Cliente|null
     * @throws Exception
     * @throws ServicesException
     */
    public function create(array $request): Cliente|null
    {
        if(!isset($request['cpf'])) {
            return null;
        }

        $existingClient = $this->showByCpf( $request['cpf'] );

        if(!isset($existingClient->_id)){
            $request['createdAt'] = date('Y-m-d H:i:s');
            if ($this->repository->create($request) == 0) {
                throw new ServicesException("O cliente não foi criado", 500);
            }
            return $this->show($this->repository->getLastInsertId());
        }

        try{
            $this->update($existingClient->_id, $request);
            return $this->show($existingClient->_id);
        } catch (ServicesException $e){
            throw new ServicesException("O cliente não foi criado", 500);
        }
    }

    /**
     * @param string $id
     * @param array $request
     * @return Cliente
     * @throws ServicesException|Exception
     */
    public function update(string $id, array $request): Cliente
    {
        $request['updatedAt'] = date('Y-m-d H:i:s');
        if($this->repository->update($id, $request) == 0) {
            throw new ServicesException("O cliente não foi alterado", 500);
        }

        return $this->show($id);
    }

    /**
     * @param string $id
     * @return array
     * @throws ServicesException
     * @throws Exception
     */
    public function delete(string $id): array
    {
        if($this->repository->delete($id) == 0) {
            throw new ServicesException("O cliente não foi excluído", 500);
        }
        $clients = array_map(fn ($stdClass) => $stdClass->_id, $this->list());
        (new PedidoService($this->repository))->clearClients($clients);

        return [];
    }

    /**
     * @param string $id
     * @return Cliente
     * @throws Exception
     */
    public function show(string $id): Cliente
    {
        return $this->entity->fill($this->repository->show($id));
    }

    /**
     * @return array
     * @throws Exception
     */
    public function list(): array
    {
        $response = [];
        foreach($this->repository->list() as $row){
            $response[] = (new Cliente())->fill($row);
        }

        return $response;
    }

    /**
     * @param string $cpf
     * @return array
     * @throws Exception
     */
    public function showByCpf(string $cpf): Cliente
    {
        $response = $this->repository->query(
            [
                'cpf' => [
                    '$eq' => $this->clearCpf($cpf)
                ]
            ]
        );

        return $this->entity->fill($response[0] ?? []);
    }

    /**
     * @param $cpf
     * @return string
     */
    private function clearCpf($cpf):string
    {
        return str_replace(['.', '-', '/'], '',trim($cpf));
    }

}
