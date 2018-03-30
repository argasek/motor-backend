<?php

namespace Motor\Backend\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckPermission {

	/**
	 * @var
	 */
	protected $auth;


	/**
	 * Create a new filter instance.
	 *
	 * @param Guard $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}


	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if (strpos($request->ip(), '10.1.') !== false) {
			return redirect('home');
		}

		$route = $request->route()->getName();
		$routeCleaned = str_replace('backend.', '', $route);
		$routeCleaned = str_replace('api.', '', $routeCleaned);

		$routeParts = explode('.', $routeCleaned);
		$routeParts = array_reverse($routeParts);

		switch ($routeParts[0])
		{
			case 'store':
			case 'create':
			case 'update':
			case 'edit':
			case 'duplicate':
				$routeParts[0] = 'write';
				break;
			case 'destroy':
				$routeParts[0] = 'delete';
				break;
			case 'index':
			case 'show':
				$routeParts[0] = 'read';
				break;
		}

		$routeParts = array_reverse($routeParts);
		$permission = implode('.', $routeParts);

		if (!has_permission($permission))
		{
			abort(403);
		}

		return $next($request);
	}
}
