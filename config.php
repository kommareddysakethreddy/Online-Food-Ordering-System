<?php
require('stripe-php-master/init.php');

$publishableKey="pk_test_51J2csJSHVD1dqQAjchwfNirCpiELgKrw915oT6eWsgio6SNMNXpnMAwl2W4C0KQAi4YYRKlqxvouXXpCtDa3AYKf00PHZitjEw";

$secretKey="sk_test_51J2csJSHVD1dqQAjvzhSn8zZ7PX4V2ekzAUTNEMf8G16Am74Q7w33FMljf5VIwer29OAtImQtnEWg24fJJXTJXjv00DJsUlHyv";

\Stripe\Stripe::setApiKey($secretKey);
?>