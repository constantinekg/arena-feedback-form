<?php
     
     namespace app\components;
      
     use yii\base\Component;
      
     class UtilsComponent extends Component {
      
        function cidr_match($ip, $range) // Проверяет - входит ли тот или иной айпишник в определённую подсетку
        {
            list ($subnet, $bits) = explode('/', $range);
            if ($bits === null) {
                $bits = 32;
            }
            $ip = ip2long($ip);
            $subnet = ip2long($subnet);
            $mask = -1 << (32 - $bits);
            $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
            return ($ip & $mask) == $subnet; // return 1 or nothing
        }
      
     }