<?php

declare(strict_types=1);

namespace App\Enums;

enum FormRules: string
{
  case OPTIONAL = 'optional';
  case EMAIL = 'email';
  case ALPHA = 'alpha';
  case TEXT = 'text';
  case TEXT_ONLY = 'textonly';
  case ALPHANUM = 'alphanum';
  case ALPHA_DASH = 'alphadash';
  case USERNAME = 'username';
  case NUMBER = 'number';
  case FLOAT = 'float';
  case DATE = 'date';
  case MIN = 'min';
  case MAX = 'max';
  case BETWEEN = 'between';
  case MATCH = 'match';
  case CONTAINS = 'contains';
  case BOOLEAN = 'boolean';
  case TRUE_FALSE = 'truefalse';
  case IP = 'ip';
  case IPV4 = 'ipv4';
  case IPV6 = 'ipv6';
  case URL = 'url';
  case DOMAIN = 'domain';
  case CREDIT_CARD = 'creditcard';
  case PHONE = 'phone';
  case UUID = 'uuid';
  case SLUG = 'slug';
  case JSON = 'json';
  case REGEX = 'regex';
  case ARRAY = 'array';
  case STRING = 'string';
  case HARD_FLOAT = 'hardfloat';
  case NUMERIC = 'numeric';
  case IN = 'in';
  case MATCHES_VALUE_OF = 'matchesvalueof';
}
