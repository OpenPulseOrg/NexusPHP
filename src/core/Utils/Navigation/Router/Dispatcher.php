<?php

namespace Nxp\Core\Utils\Navigation\Router;

use Nxp\Core\Security\Detection\SQLDetection;
use Nxp\Core\Utils\HTTP\Request;
use Nxp\Core\Utils\HTTP\Response;

class Dispatcher
{
    public static function dispatch()
    {
        $request = new Request();
        $response = new Response();

        $method = $request->getMethod();
        $uri = $request->getUri();

         // Check for SQL injection attempts in the URL
         $sqlDetector = new SQLDetection();
         if ($sqlDetector->detectSqlInjectionInURL()) {
             $response->setStatusCode(400); 
             $response->setBody("<h1>Potential malicious request detected</h1>");
             $response->send();
             return;
         }

        $isApiCall = substr($uri, 0, 5) === '/api/';

        $routes = Collection::getRoutes();

        foreach ($routes as $route) {
            $pattern = "~^" . $route["pattern"] . "$~";
            if ($method === $route["method"] && preg_match($pattern, $uri, $matches)) {
                // run middleware
                if ($route["middleware"]) {
                    foreach ($route["middleware"] as $middleware) {
                        $middleware->handle($request, function ($request) use ($route, $matches, $response) {
                            $callback = $route["callback"];

                            array_shift($matches);

                            if (is_string($callback)) {
                                list($class, $method) = explode('@', $callback);
                                $object = new $class;
                                call_user_func_array(array($object, $method), array_merge([$request, $response], $matches));
                            } else {
                                call_user_func_array($callback, array_merge([$request, $response], $matches));
                            }
                        });
                    }
                } else {
                    // no middleware, dispatch directly
                    $callback = $route["callback"];

                    array_shift($matches);

                    if (is_string($callback)) {
                        list($class, $method) = explode('@', $callback);
                        $object = new $class;
                        call_user_func_array(array($object, $method), array_merge([$request, $response], $matches));
                    } else {
                        call_user_func_array($callback, array_merge([$request, $response], $matches));
                    }
                }

                return;
            }
        }

        // If no matching route was found, send a 404 response
        if ($isApiCall) {
            $response->setStatusCode(404);
            $response->setBody("<h1>404 Not Found</h1>");

            // $response->json(["error" => "Not Found"]);
        } else {
            $response->setStatusCode(404);
            $response->setBody("<h1>404 Not Found</h1>");
        }

        $response->send();
    }
}
