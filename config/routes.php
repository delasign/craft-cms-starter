<?php
/**
 * Site URL Rules
 *
 * You can define custom site URL rules here, which Craft will check in addition
 * to routes defined in Settings → Routes.
 *
 * Read all about Craft’s routing behavior, here:
 * https://craftcms.com/docs/4.x/routing.html
 */

return [
    'GET api/getall' => 'subscription/get-all-subscribers/resolve-request',
    'POST api/subscribe' => 'subscription/new-subscriber/resolve-request',
    'PUT api/unsubscribe' => 'subscription/un-subscribe/resolve-request'
];