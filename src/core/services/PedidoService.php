<?php

namespace MsPedidosApp\core\services;

use MongoDB\Driver\Exception\Exception;
use MsPedidosApp\adapters\db\external\MongoRepository;
use MsPedidosApp\core\entities\Pedido;
use MsPedidosApp\core\exceptions\ServicesException;
use MsPedidosApp\core\interfaces\IPagamentoService;
use MsPedidosApp\core\interfaces\IPedidoService;
use MsPedidosApp\core\types\EnumStatus;

class PedidoService implements IPedidoService
{

    protected Pedido $entity;

    protected string $namespace = 'pedido';
    protected string $pKey = 'oid';

    protected MongoRepository $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
        $this->repository->setNamespace($this->namespace);
        $this->repository->setPKey($this->pKey);
        $this->entity = new Pedido();
    }

    /**
     * @param array $request
     * @return Pedido
     * @throws Exception|ServicesException
     */
    public function create(array $request): Pedido
    {
        $data['status'] = EnumStatus::INICIADO->name;
        $data['createdAt'] = $data['updatedAt'] = date('Y-m-d H:i:s');

        if ($this->repository->create($data) == 0) {
            throw new ServicesException("O pedido não foi criado", 500);
        }
        return $this->show($this->repository->getLastInsertId());
    }

    /**
     * @param $status
     * @return string
     * @throws ServicesException
     */
    private function statusTreatment($status): string
    {
        $status = $status instanceof \UnitEnum ? $status->name : $status;
        if (!array_key_exists($status, EnumStatus::getList())) {
            throw new ServicesException("Status inválido");
        }

        return $status;
    }

    /**
     * @param string $id
     * @param array $request
     * @return Pedido
     * @throws Exception|ServicesException
     */
    public function update(string $id, array $request): Pedido
    {
        $data = $request;

        if (isset($data['status'])) {
            $data['status'] = $this->statusTreatment($data['status']);
        }


        $query = $this->repository->show($id);
        $data = array_merge($query, $data);
        $toSave = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, ['itens', '_id'])) {
                if (@$query[$key] != $value) {
                    $toSave[$key] = $value;
                } else {
                    $toSave[$key] = $query[$key];
                }
            }

            if (isset($data['itens'])) {
                $toSave['itens'] = $data['itens'];
                $total = 0;
                foreach ($data['itens'] as $i => $item) {
                    $value = $item['quantidade'] * $item['valor_unitario'];
                    $toSave['itens'][$i]['valor'] = $value;
                    $total += $value;
                }
                $toSave['total_pedido'] = $total;
            }
        }

        $toSave['updatedAt'] = date('Y-m-d H:i:s');
        if ($this->repository->update($id, $toSave) == 0) {
            throw new ServicesException("O pedido não foi alterado", 500);
        }

        $pedido = $this->show($id);

        if ($toSave['status'] == 'AGUARDANDO_PAGTO') {
            $this->order($pedido, (new PagamentoService()));
        }

        return $pedido;
    }

    private function order(Pedido $pedido, PagamentoService $pagamento)
    {
        return $pagamento->order($pedido);
    }

    /**
     * @param string $id
     * @return array
     * @throws ServicesException
     */
    public function delete(string $id): array
    {
        if ($this->repository->delete($id) == 0) {
            throw new ServicesException("O pedido não foi deletado", 500);
        }
        return [];
    }

    /**
     * @param string $id
     * @return Pedido
     * @throws Exception
     */
    public function show(string $id): Pedido
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
        foreach ($this->repository->list() as $row) {
            $response[] = (new Pedido())->fill($row);
        }

        return $response;
    }

    /**
     * @return array
     */
    public function statusList(): array
    {
        return EnumStatus::getList();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function pedidos(): array
    {
        $pedidos = $this->repository->query(
            [
                'status' => [
                    '$in' => [EnumStatus::FINALIZADO->name, EnumStatus::EM_PREPARACAO->name, EnumStatus::RECEBIDO->name]
                ]
            ]
        );

        $response = [];
        foreach ($pedidos as $i => $pedido) {
            $response[$i][] = (new Pedido())->fill($pedido);
        }
        return $response;
    }

    /**
     * @param string $status
     * @return array
     * @throws Exception
     */

    public function listByStatus(string $status): array
    {
        $pedidos = $this->repository->query(
            [
                'status' => [
                    '$eq' => strtoupper($status)
                ]
            ]
        );

        $response = [];
        foreach ($pedidos as $i => $pedido) {
            $response[$i][] = (new Pedido())->fill($pedido);
        }
        return $response;
    }
}
