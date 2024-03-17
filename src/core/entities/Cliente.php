<?php

namespace MsPedidosApp\core\entities;

class Cliente extends Entity
{
    public string $_id;
    public string|null $cpf;
    public string|null $nome;
    public string|null $email;
    public string|null $endereco;
    public array|null $pagtoInfos;
    public DateTime|string|null $createdAt;
    public DateTime|string|null $updatedAt;

}
