<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use App\Invest;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class CronController extends Controller
{
    public function cron()
    {
        $now = Carbon::now();

        $invest = Invest::whereStatus(1)->where('next_time', '<=', $now)->get();
        $gnl = GeneralSetting::first();
        $gnl->last_cron = $now;
        $gnl->save();

        foreach ($invest as $data) {
            $user = User::find($data->user_id);
            $next_time = Carbon::parse($now)->addHours($data->hours);
            $in = Invest::find($data->id);
            $in->return_rec_time = $data->return_rec_time + 1; // Increase Return Count
            $in->next_time = $next_time; // When he will get again
            $in->last_time = $now; // Last Return time

            // Add Return Amount to user's Interest Balance
            $new_balance = getAmount($user->interest_wallet + $data->interest);
            $user->interest_wallet = $new_balance;
            $user->save();

                // Create The Transaction for Interest Back
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = getAmount($data->interest);
                $transaction->charge = 0;
                $transaction->post_balance = getAmount($new_balance);
                $transaction->trx_type = '+';
                $transaction->trx = getTrx();
                $transaction->wallet_type = 'interest_wallet';
                $transaction->details = getAmount($data->interest) . ' ' . $gnl->cur_text . ' Interest From '.@$in->plan->name;
                $transaction->save();

                // Give Referral Comission if Enabled
                if ($gnl->invest_return_commission == 1) {
                    $commissionType = 'interest';
                    levelCommission($user->id, $data->interest, $commissionType);
                }



            // Complete the investment if user get full amount as plan
            if ($in->return_rec_time >= $data->period && $data->period != '-1') {
                $in->status = 0; // Change Status so he do not get any more return


                // Give the capital back if plan says the same
                if ($data->capital_status == 1) {
                    $capital =  $data->amount;
                    $new_balance = getAmount($user->interest_wallet + $capital);
                    $user->interest_wallet = $new_balance;
                    $user->save();

                    $transaction = new Transaction();
                    $transaction->user_id = $user->id;
                    $transaction->amount = getAmount($capital);
                    $transaction->charge = 0;
                    $transaction->post_balance = getAmount($new_balance);
                    $transaction->trx_type = '+';
                    $transaction->trx = getTrx();
                    $transaction->wallet_type = 'interest_wallet';
                    $transaction->details = getAmount($capital) . ' ' . $gnl->cur_text . ' Capital Back From '.@$in->plan->name;
                    $transaction->save();


                }

            }

                // Lifetime Plan will run Lifetime
                if ($data->period == '-1') {
                    $in->status = 1;
                }

                $in->save();



         
        }

echo "EXECUTED";

    }
}
