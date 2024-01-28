<?php

namespace MsPedidosApp\core\types;


enum EnumCategorias: string
{
    case LANCHES = 'Lanches';
    case ACOMPANHAMENTOS = 'Acompanhamentos';
    case BEBIDAS = 'Bebidas';
    case SOBREMESAS = 'Sobremesas';

    public static function getList(): array
    {
        return array_combine(
            array_column(self::cases(), 'name'),
            array_column(self::cases(), 'value')
        );
    }
}
