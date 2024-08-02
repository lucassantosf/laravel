# Laravel V11 - SQS and SNS

# SQS,SNS - Considerations

    - If don't have already installed aws sdk for php-laravel , do it ( check on composer.json before)

        composer require aws/aws-sdk-php-laravel

    - Following the official doc (https://github.com/aws/aws-sdk-php-laravel)

        config/app.php

            'providers' => array(   
                // ...
                Aws\Laravel\AwsServiceProvider::class,
            )

        or 

        bootstrap/providers.php

            <?php

            return [
                App\Providers\AppServiceProvider::class,
                Aws\Laravel\AwsServiceProvider::class,
            ];

    - Create the Queue on AWS , and the user IAM 

    - Edit .env 

        (for SQS and SNS)

        AWS_ACCESS_KEY_ID
        AWS_SECRET_ACCESS_KEY
        AWS_REGION
        
        (for SQS)

        QUEUE_CONNECTION=sqs
        SQS_PREFIX
        SQS_QUEUE

    - Dont forget to adjuste your container command to 'sqs', example:

        php artisan queue:work sqs --verbose --tries=1 --timeout=3600 --queue=DEFAULT


# Notes:

    - Only using supervisord worked the php artisan queue:work