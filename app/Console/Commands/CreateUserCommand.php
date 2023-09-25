<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'artisan command to create users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user['id'] = Str::uuid()->toString();
        $user['names'] = $this->ask('Names of the user');
        $user['email'] = $this->ask('email of the user');
        $user['password'] = Hash::make($this->secret('password of the user'));
        $user['role'] = $this->choice('choose user role', User::ROLES, 2);
        $validator = Validator::make($user, [
            'names' => ['required'],
            'email' => ['required', 'unique:users'],
            'role' => ['required'],
            'password' => ['required ']
        ]);
        
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return;
        }
        
        User::create($user);
        $this->info('user created successfully');
    }
}
