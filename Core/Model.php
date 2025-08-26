<?php

namespace Core;
use Database;

abstract class Model
{
    protected static $table;
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public static function db()
    {
        return Database::getInstance()->getConnection();
    }

    public static function find($id)
    {
        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? new static($data) : null;
    }

    public static function all()
    {
        $stmt = self::db()->query("SELECT * FROM " . static::$table);
        $results = $stmt->fetchAll();
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        return $models;
    }

    public static function query($sql, $params = [])
    {
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        return $models;
    }
}
