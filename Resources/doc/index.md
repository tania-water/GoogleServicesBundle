Installation steps
==================

1.In your project composer.json file add the following information

    "extra": {
        "installer-paths": {
            "src/Ibtikar/GoogleServicesBundle/": ["Ibtikar/GoogleServicesBundle"]
        }
    }

    "repositories": [
        {
            "type": "vcs",
            "url":  "git@github.com:Ibtikar/GoogleServicesBundle.git"
        }
    ]

2.Require the package using composer by running

    require Ibtikar/GoogleServicesBundle:dev-master sngrl/php-firebase-cloud-messaging:dev-master

3.Add to your appkernel the next line

    new Ibtikar\GoogleServicesBundle\IbtikarGoogleServicesBundle(),

4.Add this route to your routing file

    ibtikar_google_services:
        resource: "@IbtikarGoogleServicesBundle/Resources/config/routing.yml"
        prefix:   /

5.Add the next line to your .gitignore file

    /src/Ibtikar/GoogleServicesBundle

6.Run doctrine migrations command

    bin/console doctrine:migrations:migrate --no-interaction --query-time --configuration=src/Ibtikar/GoogleServicesBundle/Resources/config/migrations.yml

7.Add PayFort sectrity settings

    -Add the following block to your project parameters.yml.dist

    firebase_api_key: null
    firebase_database_secret: null
    firebase_url_base: null
    google_distance_matrix_url_base: 'https://maps.googleapis.com/maps/api/distancematrix/json'
    google_distance_matrix_key: null
    google_directions_url_base: 'https://maps.googleapis.com/maps/api/directions/json'
    google_directions_key: null

    -Add the bundle configurations as following in your config.yml

    ibtikar_google_services:
        firebase_api_key: '%firebase_api_key%'
        firebase_database_secret: '%firebase_database_secret%'
        firebase_url_base: '%firebase_url_base%'
        google_distance_matrix_url_base: '%google_distance_matrix_url_base%'
        google_distance_matrix_key: '%google_distance_matrix_key%'
        google_directions_url_base: '%google_directions_url_base%'
        google_directions_key: '%google_directions_key%'

8.Relating your user class to device class

    1.Let your user class implements Ibtikar\GoogleServicesBundle\Entity\DeviceUserInterface

    2.In your config.yml update doctrine section by adding the following:
        doctrine:
            orm:
                resolve_target_entities:
                    Ibtikar\GoogleServicesBundle\Entity\DeviceUserInterface: {your user class full qualified name space. ex: AppBundle\Entity\User}
