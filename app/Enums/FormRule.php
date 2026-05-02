<?php

declare(strict_types=1);

namespace App\Enums;

use Leaf\Form;

enum FormRule
{
  case REQUIRED;
  case OPTIONAL;
  case EMAIL;
  case ALPHA;
  case TEXT;
  case TEXT_ONLY;
  case ALPHANUM;
  case ALPHA_DASH;
  case USERNAME;
  case NUMBER;
  case FLOAT;
  case DATE;
  case MIN;
  case MAX;
  case BETWEEN;
  case MATCH;
  case CONTAINS;
  case BOOLEAN;
  case TRUE_FALSE;
  case IP;
  case IPV4;
  case IPV6;
  case URL;
  case DOMAIN;
  case CREDIT_CARD;
  case PHONE;
  case UUID;
  case SLUG;
  case JSON;
  case REGEX;
  case ARRAY;
  case STRING;
  case HARD_FLOAT;
  case NUMERIC;
  case IN;
  case NOT_IN;
  case MATCHES_VALUE_OF;
  case PASSWORD;

  public function getName(): string
  {
    return strtolower(str_replace('_', '', $this->name));
  }

  public function getPattern(): string
  {
    return match ($this) {
      self::REQUIRED => '/^.+$/',
      self::OPTIONAL => '/^.*$/',
      self::EMAIL => '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',
      self::ALPHA,
      self::TEXT => '/^[a-zA-Z\s]+$/',
      self::TEXT_ONLY => '/^[a-zA-Z]+$/',
      self::ALPHANUM => '/^[a-zA-Z0-9\s]+$/',
      self::ALPHA_DASH => '/^[a-zA-Z0-9-_]+$/',
      self::USERNAME => '/^[a-zA-Z0-9_]+$/',
      self::NUMBER => '/^[0-9]+$/',
      self::FLOAT => '/^[0-9]+(\.[0-9]+)$/',
      self::DATE => '/^\d{4}-\d{2}-\d{2}$/',
      self::MIN => '/^.{%s,}$/',
      self::MAX => '/^.{0,%s}$/',
      self::BETWEEN => '/^.{%s,%s}$/',
      self::MATCH => '/^%s$/',
      self::CONTAINS => '/%s/',
      self::BOOLEAN => '/^(true|false|1|0)$/',
      self::TRUE_FALSE => '/^(true|false)$/',
      self::IP,
      self::IPV4 => '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',
      self::IPV6 => '/^([a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4}$/',
      self::URL => '/^(https?|ftp):\/\/(-\.)?([^\s\/?\.#-]+\.?)+(\/[^\s]*)?$/i',
      self::DOMAIN => '/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i',
      self::CREDIT_CARD => '/^([0-9]{4}-){3}[0-9]{4}$/',
      self::PHONE => '/^\+?(\d.*){3,}$/',
      self::UUID => '/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i',
      self::SLUG => '/^[a-z0-9]+(-[a-z0-9]+)*$/i',
      self::JSON => '/^[\w\s\-\{\}\[\]\"]+$/',
      self::REGEX => '/%s/',
      self::ARRAY,
      self::STRING => '/^.*$/s',
      self::HARD_FLOAT => '/^-?(?:\d+\.\d+|\d+\.0+)$/',
      self::NUMERIC => '/^-?(?:\d+(?:\.\d+)?|\.\d+)$/',
      self::IN => '/^(?:%s)$/',
      self::NOT_IN => '/^(?!%s$).+$/',
      self::MATCHES_VALUE_OF => '/^%s$/',
      self::PASSWORD => '/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',
    };
  }

  public function getMessage(): string
  {
    return match ($this) {
      self::REQUIRED => '{Field} es obligatorio.',
      self::OPTIONAL => '{Field} es válido.',
      self::EMAIL => '{Field} debe ser un correo electrónico válido.',
      self::ALPHA,
      self::TEXT,
      self::STRING => '{Field} solo debe contener letras y espacios.',
      self::TEXT_ONLY => '{Field} solo debe contener letras.',
      self::ALPHANUM => '{Field} solo debe contener letras, números y espacios.',
      self::ALPHA_DASH => '{Field} solo debe contener letras, números, guiones y guiones bajos.',
      self::USERNAME => '{Field} solo debe contener letras, números y guiones bajos.',
      self::NUMBER => '{Field} solo debe contener números.',
      self::FLOAT,
      self::HARD_FLOAT => '{Field} solo debe contener números decimales.',
      self::NUMERIC => '{Field} debe ser numérico.',
      self::DATE => '{Field} debe ser una fecha válida.',
      self::MIN => '{Field} debe tener al menos %s caracteres.',
      self::MAX => '{Field} no debe exceder %s caracteres.',
      self::BETWEEN => '{Field} debe tener entre %s y %s caracteres.',
      self::MATCH => '{Field} debe coincidir con el campo %s.',
      self::MATCHES_VALUE_OF => '{Field} debe coincidir con el valor de %s.',
      self::CONTAINS => '{Field} debe contener %s.',
      self::BOOLEAN,
      self::TRUE_FALSE => '{Field} debe ser un valor booleano.',
      self::IN => '{Field} debe ser uno de los siguientes: %s.',
      self::NOT_IN => '{Field} no debe ser uno de los siguientes: %s.',
      self::IP => '{Field} debe ser una dirección IP válida.',
      self::IPV4 => '{Field} debe ser una dirección IPv4 válida.',
      self::IPV6 => '{Field} debe ser una dirección IPv6 válida.',
      self::URL => '{Field} debe ser una URL válida.',
      self::DOMAIN => '{Field} debe ser un dominio válido.',
      self::CREDIT_CARD => '{Field} debe ser un número de tarjeta válido.',
      self::PHONE => '{Field} debe ser un número de teléfono válido.',
      self::UUID => '{Field} debe ser un UUID válido.',
      self::SLUG => '{Field} debe ser un slug válido.',
      self::JSON => '{Field} debe ser una cadena JSON válida.',
      self::REGEX => '{Field} debe coincidir con el patrón %s.',
      self::ARRAY => '{Field} debe ser un arreglo.',
      self::PASSWORD => 'La contraseña debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 símbolo.',
    };
  }

  public function getHandler(): string|callable
  {
    return match ($this) {
      self::ARRAY => function (mixed $value, mixed $internalRules = null, ?string $fieldName = null): bool {
        if (!is_array($value)) {
          return false;
        }

        if ($internalRules === null) {
          return true;
        }

        $validator = new Form();

        foreach (self::cases() as $rule) {
          $validator->addRule($rule->getName(), $rule->getHandler(), $rule->getMessage());
        }

        foreach ($value as $valueItem) {
          if (!$validator->validateRule($internalRules, $valueItem, $fieldName ?? 'item')) {
            return false;
          }
        }

        return true;
      },
      self::STRING => static fn(mixed $value): bool => is_string($value),
      self::HARD_FLOAT => static fn(mixed $value): bool => is_float($value),
      self::NUMERIC => static fn(mixed $value): bool => is_numeric($value),
      self::IN => static fn(mixed $value, mixed $param): bool => in_array($value, (array) $param, true),
      self::NOT_IN => static fn(mixed $value, mixed $param): bool => !in_array($value, (array) $param, true),
      self::MATCHES_VALUE_OF => static fn(mixed $value, mixed $param): bool => \Leaf\Http\Request::get(strval($param)) === $value,
      default => $this->getPattern(),
    };
  }
}
