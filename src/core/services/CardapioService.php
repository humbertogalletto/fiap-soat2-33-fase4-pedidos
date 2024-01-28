<?php

namespace MsPedidosApp\core\services;

use MongoDB\Driver\Exception\Exception;
use MsPedidosApp\adapters\db\external\MongoRepository;
use MsPedidosApp\core\entities\Cardapio;
use MsPedidosApp\core\interfaces\ICardapioService;
use MsPedidosApp\core\types\EnumCategorias;

class CardapioService implements ICardapioService
{

    protected Cardapio $entity;

    protected string $namespace = 'cardapio';
    protected string $pKey = 'oid';

    protected MongoRepository $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
        $this->repository->setNamespace($this->namespace);
        $this->repository->setPKey($this->pKey);
        $this->entity = new Cardapio();
    }


    /**
     * @param array $request
     * @return Cardapio
     * @throws Exception
     */
    public function create(array $request): Cardapio
    {
        if(!isset($request['categoria'])){
            throw new \Exception("Preencha a categoria do produto");
        }
        $categoria = $request['categoria'] instanceof \UnitEnum ? $request['categoria']->name : $request['categoria'];
        if (!array_key_exists($categoria, EnumCategorias::getList())) {
            throw new \Exception("Categoria inválida");
        } else {
            $request['categoria'] = $categoria;
        }

        $request['created_at'] = $request['updated_at'] = date('Y-m-d H:i:s');

        if($this->repository->create($request) == 0) {
            throw new \Exception("O item do cardápiio não foi criado", 500);
        }
        return $this->show($this->repository->getLastInsertId());
    }

    /**
     * @param int $id
     * @param array $request
     * @return Cardapio
     */
    public function update(string $id, array $request): Cardapio
    {
        if(isset($request['categoria'])) {
            $categoria = $request['categoria'] instanceof \UnitEnum ? $request['categoria']->name : $request['categoria'];
            if (!array_key_exists($categoria, EnumCategorias::getList())) {
                throw new \Exception("Categoria inválida");
            } else {
                $request['categoria'] = $categoria;
            }
        }
        $request['updated_at'] = date('Y-m-d H:i:s');
        if($this->repository->update($id, $request) == 0) {
            throw new \Exception("O item do cardápio não foi alterado", 500);
        }

        return $this->show($id);
    }

    /**
     * @param string $id
     * @return array
     * @throws \Exception
     */
    public function delete(string $id): array
    {
        if($this->repository->delete($id) == 0) {
            throw new \Exception("O item do cardápio não foi deletado", 500);
        }
        return [];
    }

    /**
     * @param int $id
     * @return Cardapio
     * @throws Exception
     */
    public function show(string $id): Cardapio
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
            $response[] = (new Cardapio())->fill($row);
        }

        return $response;
    }

    /**
     * @return array
     */
    public function categoriasList(): array
    {
        return EnumCategorias::getList();
    }
}