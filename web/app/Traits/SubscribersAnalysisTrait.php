<?php

namespace App\Traits;

use App\Models\Subscriber;

trait SubscribersAnalysisTrait
{
    public static function getSubscribersStatistics(): array
    {

        $newSubscribersByWeek = Subscriber::countNewSubscriberByTime('week')->get();
        $newSubscribersByMonth = Subscriber::countNewSubscriberByTime('month')->get();
        $newSubscribersByYear = Subscriber::countNewSubscriberByTime('year')->get();

        //Group by platform
        $newSubscribersByPlatformPerWeek = Subscriber::countNewSubscribersByPlatform('week')->get();
        $newSubscribersByPlatformPerMonth = Subscriber::countNewSubscribersByPlatform('month')->get();
        $newSubscribersByPlatformPerYear = Subscriber::countNewSubscribersByPlatform('year')->get();

        //group by channel
        $newSubscribersByChannelPerWeek = Subscriber::countNewSubscribersByChannel('week')->get();
        $newSubscribersByChannelPerMonth = Subscriber::countNewSubscribersByChannel('month')->get();
        $newSubscribersByChannelPerYear = Subscriber::countNewSubscribersByChannel('year')->get();

        return [
            'new_subscribers_by_week_count' => $newSubscribersByWeek->count(),
            'new_subscribers_by_month_count' => $newSubscribersByMonth->count(),
            'new_subscribers_by_year_count' => $newSubscribersByYear->count(),

            //platforms
            'new_subscribers_by_platforms_per_week' => $newSubscribersByPlatformPerWeek,
            'new_subscribers_by_platforms_per_month' => $newSubscribersByPlatformPerMonth,
            'new_subscribers_by_platforms_per_year' => $newSubscribersByPlatformPerYear,

            //channels
            'new_subscribers_by_channels_per_week' => $newSubscribersByChannelPerWeek,
            'new_subscribers_by_channels_per_month' => $newSubscribersByChannelPerMonth,
            'new_subscribers_by_channels_per_year' => $newSubscribersByChannelPerYear,

        ];

    }
}
