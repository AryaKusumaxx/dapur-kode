<?php

namespace Tests\Unit;

use App\Http\Middleware\ActiveUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ActiveUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_active_users_to_access_pages()
    {
        // Create an active user
        $user = User::factory()->create(['is_active' => true]);
        
        // Authenticate the user
        Auth::login($user);
        
        $request = Request::create('/dashboard', 'GET');
        $middleware = new ActiveUser();
        
        // Create a mock response
        $response = response('passed');
        
        // Capture the response
        $result = $middleware->handle($request, function ($req) use ($response) {
            return $response;
        });
        
        // Assert that middleware allowed the request to pass
        $this->assertSame($response, $result);
    }
    
    /** @test */
    public function it_redirects_inactive_users_to_login_with_error_message()
    {
        // Create an inactive user
        $user = User::factory()->create(['is_active' => false]);
        
        // Authenticate the user
        Auth::login($user);
        
        $request = Request::create('/dashboard', 'GET');
        $middleware = new ActiveUser();
        
        // Capture the response
        $response = $middleware->handle($request, function ($req) {
            return 'should not reach here';
        });
        
        // Assert user is logged out
        $this->assertFalse(Auth::check());
        
        // Assert the response is a redirect
        $this->assertEquals(302, $response->getStatusCode());
        
        // Assert it redirects to login
        $this->assertEquals(route('login'), $response->headers->get('Location'));
        
        // Assert it contains an error message in the session
        $this->assertEquals(
            'Akun Anda tidak aktif. Silahkan hubungi administrator.',
            session('error')
        );
    }
}
