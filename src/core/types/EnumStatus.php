<?php

namespace MsPedidosApp\core\types;


enum EnumStatus: string
{
    case AGUARDANDO_PAGTO = 'aguardando pagamento';
    case INICIADO = 'iniciado';
    case RECEBIDO = 'recebido';
    case EM_PREPARACAO = 'em preparação';
    case ENTREGUE = 'entregue';
    case FINALIZADO = 'finalizado';
    case AGUARDANDO_PREPARO = 'aguardando preparo';

    public static function getList(): array
    {
        return array_combine(
            array_column(self::cases(), 'name'),
            array_column(self::cases(), 'value')
        );
    }
}
