<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session('logged_in')) {
            // Store current URL for redirect after login
            session()->set('redirect_url', current_url());
            
            // Redirect to login page
            return redirect()->to('/login')->with('error', 'Please login to access this page.');
        }

        // If specific role is required, check it
        if ($arguments && is_array($arguments)) {
            $requiredRole = $arguments[0] ?? null;
            
            switch ($requiredRole) {
                case 'admin':
                    if (!session('is_admin')) {
                        return redirect()->to('/')->with('error', 'Access denied. Admin privileges required.');
                    }
                    break;
                    
                case 'artist':
                    if (!session('is_artist') || !session('artist_profile_approved')) {
                        return redirect()->to('/')->with('error', 'Access denied. Approved artist status required.');
                    }
                    break;
                    
                case 'user':
                    // Just logged in user, no additional checks
                    break;
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
}