<?php

namespace Incident\Http\Middleware;

use Closure;
use JWTAuth;
use DB;
use Response;


class CheckProfilePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::parseToken()->toUser();

        $patch = $request->path();

        //Si el patch contiene parametros entonces los quitamos
        if (strpos($patch, '/') !== false)
            $patch = substr($patch, 0, strpos($patch, '/'));

        //Validamos si el perfil del usuario tiene permisos para ejecutar la peticion
        $autorized = DB::table('routes')
            ->select(['routes.name', 'route_profile.method'])
            ->join('route_profile', 'routes.id', '=', 'route_profile.route_id')
            ->join('profiles', 'route_profile.profile_id', '=', 'profiles.id')
            ->join('user_profiles', 'profiles.id', '=', 'user_profiles.profile_id')
            ->where('user_profiles.user_id', '=', $user->id)
            ->where('routes.name', '=', $patch)
            ->where(function ($query) use ($request) {
                $query->where('route_profile.method', '=', $request->method())
                    ->orWhere('route_profile.method', '=', '*');
            })
            ->groupBy('routes.name')
            ->count();

        if ($autorized == 0)
            return response()->json('unauthorized', 401);

        return $next($request);
    }
}
