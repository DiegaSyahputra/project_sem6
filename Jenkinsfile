pipeline {
    agent any

    stages {

        stage('Check Workspace') {
            steps {
                sh 'pwd'
                sh 'ls'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t project-sem6 .'
            }
        }

    }
}