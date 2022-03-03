<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Jobs\Generate2faJob;

class Generate2faSecretKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fa:authenticate_all {--send_email : send by email to all users} {--force : run without asking for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the secret keys for every user without two-factor authentication enabled.';

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
        if (!$this->option('force')) {
            $this->info('A new secret will be generated for all accounts that are not currently using two-factor authentication.');
            $confirm = $this->confirm('Do you wish to continue?');
            if (!$confirm) {
                return;
            }
        }

        // get list of emails that do not have any key.
        $users = User::whereNull('google2fa_secret')->get();
        if (count($users) == 0) {
            $this->info('All user have valid secrets.');
            return;
        }

        $google2fa = app('pragmarx.google2fa');
        foreach ($users as $user) {
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

        $this->info('All keys have been generated.');
    }
}
