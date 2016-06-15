<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Mail;

class UsersFromCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users-from-csv {file=users.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Integrate Users from a csv file. Emails will be sent accordingly with an activation link';

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
        $users = array_map('str_getcsv', file($this->argument('file')));

        foreach ($users as $user){
            $data = explode(';', $user[0]);

            $new_user = new User();
            $new_user->name = $data[0];
            $new_user->email = $data[1];

            $new_user->activation_token = bin2hex(openssl_random_pseudo_bytes(50));

            $new_user->save();

            Mail::send(
                'auth.emails.register',
                ['token' => $new_user->activation_token, 'id' => $new_user->id],
                function ($m) use ($new_user) {
                    $m->from('no-reply@der-naschmarkt.at', 'Der Naschmarkt');

                    $m->to($new_user->email, $new_user->name)->subject('Naschmarkt Account');
                }
            );
        }
    }
}
