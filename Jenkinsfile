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

pipeline {
    agent any

    stages {

        stage('Deploy Application') {
            steps {

                sh '''
                cd /var/www/html/project_sem6

                docker compose up -d --build app nginx

                echo "Waiting for MySQL..."

                sleep 15

                docker compose exec -T app php artisan migrate --force

                docker compose exec -T app php artisan optimize:clear
                '''
            }
        }

    }
}