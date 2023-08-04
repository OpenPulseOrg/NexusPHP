<?php

namespace Nxp\Core\Utils\Navigation\Router;

use Nxp\Core\Security\Detection\SQLDetection;
use Nxp\Core\Utils\HTTP\Request;
use Nxp\Core\Utils\HTTP\Response;

class RouteDispatcher
{
    public static function dispatch()
    {
        $request = new Request();
        $response = new Response();

        // Check URL for possible SQL Detection
        $SQLDetection = new SQLDetection();

        $SQLDetection->detectSqlInjectionInURL();

        $method = $request->getMethod();
        $uri = $request->getUri();

        $isApiCall = substr($uri, 0, 5) === '/api/';

        $routes = RouteCollection::getRoutes();

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
