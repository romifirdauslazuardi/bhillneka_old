<?php

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Throwable;

class CreateOwnerUserCommand extends Command
{
    protected $signature = 'owner:create';

    protected $description = 'Create a owner user';

    /**
     * @throws Throwable
     */
    public function handle(): int
    {
        DB::beginTransaction();
        try {
            $name = $this->ask('What is the name of the owner?');
            if (empty($name)) {
                $this->error('Name is required');

                return CommandAlias::INVALID;
            }

            $phone = $this->ask('What is the phone of the owner?');
            if (empty($phone)) {
                $this->error('phone is required');

                return CommandAlias::INVALID;
            }
            if (strlen($phone) < 8) {
                $this->error('phone is too short. It must be at least 8 characters');

                return CommandAlias::INVALID;
            }

            $email = $this->ask('What is the email of the owner?');
            if (empty($email)) {
                $this->error('Email is required');

                return CommandAlias::INVALID;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Email is invalid');

                return CommandAlias::INVALID;
            }

            $password = $this->secret('What is the password of the owner?');
            if (empty($password)) {
                $this->error('Password is required');

                return CommandAlias::INVALID;
            }
            if (strlen($password) < 8) {
                $this->error('Password is too short. It must be at least 8 characters');

                return CommandAlias::INVALID;
            }

            $this->info('Creating a owner user with the following credentials:');
            $this->info("Name: $name");
            $this->info("Email: $email");
            $this->info("Phone: $phone");

            if ($this->confirm('Do you wish to create the user?')) {
                $this->info('Creating the user...');
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]);
                $user->assignRole(RoleEnum::OWNER);
                DB::commit();
                $this->info('Owner user created successfully.');
            } else {
                $this->error('Owner creation cancelled!');
            }

            return CommandAlias::SUCCESS;
        } catch (Exception $e) {
            DB::rollBack();
            $this->error('Could not create the user.');
            $this->error($e->getMessage());

            return CommandAlias::FAILURE;
        }
    }
}
