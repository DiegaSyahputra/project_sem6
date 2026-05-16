// pipeline {
//     agent any

//     stages {

//         stage('Check Workspace') {
//             steps {
//                 sh 'pwd'
//                 sh 'ls'
//             }
//         }

//         stage('Build Docker Image') {
//             steps {
//                 sh 'docker build -t project-sem6 .'
//             }
//         }

//     }
// }

// pipeline {
//     agent any

//     stages {

//         stage('Deploy Application') {
//             steps {
//                 sh 'docker compose down'
//                 sh 'docker compose up -d --build'
//             }
//         }

//     }
// }


// pipeline {
//     agent any

//     stages {

//         stage('Deploy Application') {
//             steps {

//                 sh '''
//                 cd /var/www/html/project_sem6

//                 git pull origin main

//                 docker compose build --no-cache

//                 docker compose up -d

//                 docker compose exec -T app php artisan migrate --force

//                 docker compose exec -T app npm run build

//                 docker compose exec -T app php artisan optimize:clear
//                 '''
//             }
//         }

//     }
// }


pipeline {
    agent any

    stages {

        stage('Deploy Application') {
            steps {

                sh '''
                cd /var/www/html/project_sem6

                git pull origin main

                docker compose up -d --build --force-recreate

                docker compose exec -T app php artisan migrate --force

                docker compose exec -T app php artisan optimize:clear
                '''
            }
        }

    }
}