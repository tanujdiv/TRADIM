<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Notification;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Subscribe
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Channel $channel
    ): JsonResponse {

        $user = $request->user();


        /*
        |--------------------------------------------------------------------------
        | Prevent Self Subscription
        |--------------------------------------------------------------------------
        */

        if (
            $channel->user_id === $user->id
        ) {

            return response()->json([

                'success' => false,

                'message' =>
                    'You cannot subscribe to your own channel.',

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Subscription
        |--------------------------------------------------------------------------
        */

        $subscription = Subscription::where(
            'user_id',
            $user->id
        )
            ->where(
                'channel_id',
                $channel->id
            )
            ->first();


        if ($subscription) {

            return response()->json([

                'success' => true,

                'message' =>
                    'Already subscribed.',

                'data' => [

                    'subscribed' =>
                        true,

                ],

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Subscription
        |--------------------------------------------------------------------------
        */

        Subscription::create([

            'user_id' =>
                $user->id,

            'channel_id' =>
                $channel->id,

        ]);


        $channel->increment(
            'subscriber_count'
        );


        /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */

        if (
            $channel->user_id !==
            $user->id
        ) {

            Notification::create([

                'user_id' =>
                    $channel->user_id,

                'type' =>
                    'new_subscriber',

                'title' =>
                    'New subscriber',

                'message' =>
                    $user->name .
                    ' subscribed to your channel.',

                'url' =>
                    route(
                        'channels.show',
                        $channel->handle
                    ),

                'actor_id' =>
                    $user->id,

                'is_read' =>
                    false,

                'read_at' =>
                    null,

            ]);
        }


        return response()->json([

            'success' => true,

            'message' =>
                'Subscribed successfully.',

            'data' => [

                'subscribed' =>
                    true,

                'subscriber_count' =>
                    (int) $channel
                        ->fresh()
                        ->subscriber_count,

            ],

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | Unsubscribe
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        Channel $channel
    ): JsonResponse {

        $user = $request->user();


        $subscription = Subscription::where(
            'user_id',
            $user->id
        )
            ->where(
                'channel_id',
                $channel->id
            )
            ->first();


        if (!$subscription) {

            return response()->json([

                'success' => true,

                'message' =>
                    'Already unsubscribed.',

                'data' => [

                    'subscribed' =>
                        false,

                ],

            ]);
        }


        $subscription->delete();


        if (
            $channel->subscriber_count > 0
        ) {

            $channel->decrement(
                'subscriber_count'
            );
        }


        return response()->json([

            'success' => true,

            'message' =>
                'Unsubscribed successfully.',

            'data' => [

                'subscribed' =>
                    false,

                'subscriber_count' =>
                    (int) $channel
                        ->fresh()
                        ->subscriber_count,

            ],

        ]);
    }
}