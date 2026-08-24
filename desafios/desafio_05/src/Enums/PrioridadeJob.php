<?php 

declare(strict_types=1);

namespace Desafio05\Enums;

enum PrioridadeJob: int{
  case ALTA = 1;
  case MEDIA = 2;
  case BAIXA = 3; 
}
