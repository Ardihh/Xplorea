<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
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
        // Check if user is logged in first
        if (!session('logged_in')) {
            // Store current URL for redirect after login
            session()->set('redirect_url', current_url());
            
            // Redirect to login page
            return redirect()->to('/login')->with('error', 'Please login to access this page.');
        }

        // Check if user is admin
        if (!session('is_admin')) {
            // Log unauthorized access attempt
            log_message('warning', 'Unauthorized admin access attempt by user: ' . (session('username') ?? 'unknown') . ' IP: ' . $request->getIPAddress());
            
            return redirect()->to('/')->with('error', 'Access denied. Administrator privileges required.');
        }

        // Optional: Additional admin verification
        // You could add more checks here like:
        // - Check if admin account is active
        // - Check for specific admin permissions
        // - Rate limiting for admin actions
        
        // Log admin access
        log_message('info', 'Admin access: ' . session('username') . ' accessed ' . $request->getUri());
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
        // You could add admin activity logging here
        // Or modify response headers for admin pages
    }
}