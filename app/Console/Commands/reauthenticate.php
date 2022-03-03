<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use App\Jobs\Generate2faJob;

class ReAuthenticate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fa:reauthenticate {--email= : The email of the user to reauthenticate} {--send_email : send by email to the user} {--force : run without asking for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate the secret key for a user\'s two factor authentication';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->option('email');
        if (!$email) $email = $this->ask('Enter user\'s email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error('Cannot find a user with address ' . $email);
            return;
        }

        if (!$this->option('force')) {
            $this->info('A new secret will be generated for ' . $user->email);
            $this->info('This action will invalidate the previous secret key.');
            $confirm = $this->confirm('Do you wish to continue?');
            if (!$confirm) {
                return;
            }
        }

        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();
        $user->google2fa_secret = $secret;

        if ($this->option('send_email')) {
            try {
                $email_body = 'A new two-factor authentication code has been generated for your account.';
                dispatch((new Generate2faJob($email_body, $user->email, $user->name, $secret))->onQueue("high"));
                $this->info('An email has been sent to ' . $user->email);
            } catch (\Exception $e) {
                $this->error('Could not send an email to ' . $user->email);
            }
        }
        $this->info('The new 2fa code for ' . $user->email . ' is ' . $secret);
        $user->save();
    }
}
