<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Etudiant;
use App\Services\ChatbotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery\MockInterface;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_chatbot_endpoint(): void
    {
        $response = $this->postJson('/etudiant/chatbot/message', [
            'message' => 'Bonjour',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'reply' => "Accès non autorisé. Seuls les étudiants peuvent interagir avec l'assistant."
        ]);
    }

    public function test_non_student_cannot_access_chatbot_endpoint(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'actif' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/etudiant/chatbot/message', [
            'message' => 'Bonjour',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'reply' => "Accès non autorisé. Seuls les étudiants peuvent interagir avec l'assistant."
        ]);
    }

    public function test_student_can_send_message_and_receive_mocked_reply(): void
    {
        $user = User::factory()->create([
            'role' => 'etudiant',
            'actif' => true,
        ]);

        $etudiant = Etudiant::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->mock(ChatbotService::class, function (MockInterface $mock) use ($user) {
            $mock->shouldReceive('chat')
                ->with('Bonjour assistant', $user->id)
                ->once()
                ->andReturn('Bonjour! En quoi puis-je vous aider aujourd\'hui?');
        });

        $response = $this->actingAs($user)->postJson('/etudiant/chatbot/message', [
            'message' => 'Bonjour assistant',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'reply' => "Bonjour! En quoi puis-je vous aider aujourd'hui?"
        ]);
    }

    public function test_missing_api_key_returns_friendly_error_reply(): void
    {
        // Unbind the mocked ChatbotService to test the real Service logic behavior
        $this->app->forgetInstance(ChatbotService::class);

        $user = User::factory()->create([
            'role' => 'etudiant',
            'actif' => true,
        ]);

        $etudiant = Etudiant::factory()->create([
            'user_id' => $user->id,
        ]);

        // Temporarily clear configuration to trigger the missing key exception
        config(['services.openai.key' => null]);
        config(['services.openai.api_key' => null]);
        putenv('OPENAI_API_KEY=');

        $response = $this->actingAs($user)->postJson('/etudiant/chatbot/message', [
            'message' => 'Quelles sont mes notes ?',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['reply']);
        $this->assertStringContainsString('clé API de configuration est manquante ou incorrecte', $response->json('reply'));
    }
}
