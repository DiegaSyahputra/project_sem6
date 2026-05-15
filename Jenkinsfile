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

pipeline {
    agent any

    stages {

        stage('Deploy Application') {
            steps {

                sh 'docker-compose down'

                sh 'docker-compose up -d --build'

            }
        }

    }
}