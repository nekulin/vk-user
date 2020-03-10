<?php

namespace Helper;

use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiPrivateProfileException;

class VKHelper
{
    public static function getExecuteFullItems(string $method, int $count, array $params, string $accessToken, callable $callback=null) :array
    {
        $vk = new VKApiClient();

        $execLimit = 25; // огранчения выполнения execute

        $result = [];

        $params['count'] = 1;
        $params['offset'] = 0;
        $params['order'] = null;

        $json = json_encode($params);

        try {

            // calc request
            $response = $vk->getRequest()->post('execute', $accessToken, [
                'code' => "return {$method}({$json});",
            ]);

        } catch (VKApiPrivateProfileException $e) {

            return [];
        }

        $countFor = ceil(($response['count'] / $count) / $execLimit);

        $offset = 0;

        $params['count'] = $count;

        for ($i=0; $i < $countFor; $i++) {

            $code = 'var result = [];';

            for ($j=0; $j < $execLimit; $j++) {

                $params['offset'] = $offset;
                $json = json_encode($params);

                $code .= "result = result + {$method}({$json})['items'];";
                $code .= PHP_EOL;

                $offset += $count;
            }

            $code .= 'return result;';

            $response = $vk->getRequest()->post('execute', $accessToken, [
                'code' => $code
            ]);

            if (!is_null($callback)) {

                $callback($response, $countFor);
            }

            $result = array_merge($result, $response);
        }

        return $result;
    }
}