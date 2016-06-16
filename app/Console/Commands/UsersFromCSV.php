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
        //split file into an array with all rows
        $users = array_map('str_getcsv', file($this->argument('file')));

        foreach ($users as $user){

            $user_check = User::where('email', $user[2])->get();

            if ($user_check->isEmpty()){
                //save user data
                $new_user = new User();
                $new_user->name = $user[0] . ' ' . $user[1];
                $new_user->email = $user[2];
                $new_user->activation_token = bin2hex(openssl_random_pseudo_bytes(50));

                if($user[3] == 'Staff'){
                    $new_user->is_staff = 1;
                }

                $new_user->save();
                $this->sendMail($new_user);
            } else {
                if($user[3] == 'Staff'){
                    $user_check->first()->is_staff = 1;
                    $user_check->first()->save();

                    if(isset($user_check->activation_token)){
                        $this->sendMail($user_check);
                    }
                }
            }
        }
    }
    
    private function sendMail($user){
        //send email
        Mail::send(
            'auth.emails.register',
            ['token' => $user->activation_token, 'id' => $user->id],
            function ($m) use ($user) {
                $m->from('no-reply@der-naschmarkt.at', 'Der Naschmarkt');

                $m->to($user->email, $user->name)->subject('Naschmarkt Account');
            }
        );
    }
}
