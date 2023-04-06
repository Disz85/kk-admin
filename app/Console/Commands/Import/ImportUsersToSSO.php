<?php

namespace App\Console\Commands\Import;

use App\Auth\Services\SSOService;
use App\Mail\UserTemporarySSOPasswordMail;
use App\Models\User;
use Faker\Generator;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ImportUsersToSSO extends Command
{
    protected Generator $faker;

    protected $signature = 'import:users-to-sso {{--send-mails}}';

    protected $description = 'Imports users to SSO with temporary password, and sends email to users with the password';

    public function __construct(Generator $faker)
    {
        parent::__construct();
        $this->faker = $faker;
    }

    public function handle(SSOService $ssoService): void
    {
        User::query()
            ->cursor()
            ->each(function (User $user) use ($ssoService) {
                $result = $this->createUserInKeycloak($ssoService, $user);

                if ($result['success'] && $this->option('send-mails')) {
                    Mail::queue(new UserTemporarySSOPasswordMail($user, $result['password']));
                }
            });
    }

    protected function createUserInKeycloak(SSOService $ssoService, User $user): array
    {
        $password = $this->faker->password(12, 12);

        try {
            $response = $ssoService->addUser([
                'username' => $user->username,
                'email' => $user->email,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'enabled' => true,
                'credentials' => [
                    [
                        'type' => 'password',
                        'value' => $password,
                        'temporary' => true,
                    ],
                ],
            ]);
        } catch (ClientException $clientException) {
            // Skip already registered users based on username in sso (email)
            throw_unless($clientException->getCode() === 409, $clientException); // CONFLICT
        }

        if (isset($response) && $response->getStatusCode() === 201) {
            // User created successfully
            return [
                'success' => true,
                'password' => $password,
            ];
        }

        // Error creating user
        return [
            'success' => false,
        ];
    }
}
