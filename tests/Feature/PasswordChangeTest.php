<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the database to have roles, filieres, etc. if required, 
        // or we can just create users directly.
    }

    public function test_guest_cannot_access_password_change_page(): void
    {
        $response = $this->get(route('profile.password'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_password_change_page(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ETUDIANT,
            'actif' => true,
        ]);

        $response = $this->actingAs($user)->get(route('profile.password'));
        $response->assertStatus(200);
        $response->assertSee('Modifier le mot de passe');
    }

    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
            'role' => User::ROLE_ETUDIANT,
            'actif' => true,
        ]);

        $response = $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));
    }

    public function test_user_cannot_change_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
            'role' => User::ROLE_ETUDIANT,
            'actif' => true,
        ]);

        $response = $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'wrong-current-password',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_password_change_validation_requires_confirmed_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
            'role' => User::ROLE_ETUDIANT,
            'actif' => true,
        ]);

        $response = $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-secure-password',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }
}
