<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/register.php';

final class RegisterTest extends TestCase
{
    public function testValidateRegistrationSuccess()
    {
        $data = [
            'nombre' => 'Manu',
            'username' => 'manu_27',
            'email' => 'manu@example.com',
            'password' => 'secret123'
        ];

        $errors = validate_registration($data);
        $this->assertIsArray($errors);
        $this->assertEmpty($errors, 'No debe haber errores con datos válidos');
    }

    public function testValidateRegistrationMissingFields()
    {
        $data = [
            'nombre' => '',
            'username' => 'a',
            'email' => 'invalid-email',
            'password' => '123'
        ];

        $errors = validate_registration($data);
        $this->assertNotEmpty($errors);
        $this->assertContains('El nombre es obligatorio.', $errors);
        $this->assertTrue(array_filter($errors, fn($e) => str_contains($e, 'nombre de usuario')) != false);
        $this->assertTrue(array_filter($errors, fn($e) => str_contains($e, 'email') || str_contains($e, 'Email') || str_contains($e, 'email')) != false);
    }

    public function testGenerateRememberTokenLength()
    {
        $token = generate_remember_token(16); // 16 bytes => 32 hex chars
        $this->assertIsString($token);
        $this->assertEquals(32, strlen($token));

        $token2 = generate_remember_token(32);
        $this->assertEquals(64, strlen($token2));
        $this->assertNotEquals($token, $token2);
    }
}
