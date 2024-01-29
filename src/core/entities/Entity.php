<?php

namespace MsPedidosApp\core\entities;

class Entity
{
    public function fill($data)
    {
        foreach((array)$data as $att => $value){
            $this->$att = $value;
        }

        return $this;
    }

    public function toArray()
    {
        $array = [];
        foreach(get_object_vars($this) as $att => $value){
            $array[$att] = $value;
        }
        return $array;
    }
}
