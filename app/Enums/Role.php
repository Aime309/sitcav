<?php

declare(strict_types=1);

namespace App\Enums;

enum Role {
  case ADMIN;
  case ATTENDANT;
  case SELLER;
  case CLIENT;
}
